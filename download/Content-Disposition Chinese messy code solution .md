### 正确处理浏览器在下载文件时HTTP头的编码问题（Content-Disposition）

#### 原文链接
[IE浏览器中文网站](http://www.iefans.net/xiazai-wenjian-http-bianma-content-disposition/)

最近在做项目时遇到了一个 case ：需要实现一个强制在浏览器中的下载功能（即强制让浏览器弹出下载对话框），并且文件名必须保持和用户之前上传时相同（可能包含非 ASCII 字符）。 前一个需求很容易实现：使用 HTTP Header 的 Content-Disposition: attachment 即可，还可以配合 Content-Type: application/octet-stream 来确保万无一失。而后一个需求就比较蛋疼了，牵扯到 Header 的编码问题（文件名是作为 filename 参数放在 Content-Disposition 里面的）。众所周知， HTTP Header 中的 Content-Type 可以指定内容的编码，可 Header 本身的编码又该如何制定？甚至， Header 究竟是否允许非 ASCII 编码呢？ 如果放任编码问题不管，那么恭喜你，你一定会遇到在某个系统及浏览器下下载文件时文件名乱码的情况。如果你尝试搜索解决，那么再一次恭喜你，你会找到一堆自相矛盾的解决方案（我可以负责任地告诉你，其中的99%都是不符合标准的 trick 罢了）。让我们来看看到底应该如何优雅完美地解决这个问题吧！ 为了探索这个问题，我走了不少弯路。从自己尝试，到 Google 、百度（分别尝试过中英文搜索），再到阅读 Discuz 等经典项目的源码，众说纷纭、莫衷一是。最后我才想到回归 RFC ，从标准文档中找办法，果然有所收获。由于探究过程实在太曲折，我就先把标准做法写下来。
应该这样设置 Content-Disposition ：
Content-Disposition: attachment;
                      filename="$encoded_fname";
                      filename*=utf-8''$encoded_fname
其中，$encoded_fname指的是将 UTF-8 编码的原始文件名按照 RFC 3986 进行百分号 urlencode 后得到的（ PHP 中使用 rawurlencode() 函数）。这几行也可以合并为一行，推荐使用一个空格隔开。 另外，为了兼容 IE6 ，请保证原始文件名必须包含英文扩展名！
好了，接下来我们来看看为什么要这么做以及为什么能这么做。 首先，根据 HTTP 1.1 协议规范（ RFC 2616 Section 4 ）， HTTP 消息格式其实是基于古老的 ARPA INTERNET TEXT MESSAGES （ RFC 822 Section 3 ），根据其规定，消息只能是 ASCII 编码的。 RFC 2616 Section 2.2 又一次强调， TEXT 中若要使用其他字符集，必须使用 RFC 2047 的规则将字符串编码为 ASCII 码（事实上这个规则原本是针对 MIME 的扩展，使用的是 base64 编码，格式与百分号编码有很大不同）。总而言之，按照标准， HTTP Header 中的文本数据必须是 ASCII 编码的。
filename="TEXT"
 ;这是 RFC 2616 标准，TEXT必须是 ASCII 字符且被认为就是“原文”
filename*=charset'lang'encoded-text
 ;这是按照 RFC 2047 扩展后的，注意格式上的细微区别，采用 base64 编码（编码结果也是 ASCII 字符）
然而，事实上在1999年 HTTP 1.1 标准推出之时， Content-Dispostion 这个 Header 尚不是正式标准的一部分，只不过是因为被广泛使用而从 MIME 标准中直接借用过来了而已（ RFC 2616 Section 19.5.1 ）。因而几乎没有浏览器去支持 Content-Disposition 的多语言编码特性这样一个“扩展特性的扩展特性”（事实上， HTTP 1.1 草案中建议的使用 RFC 2047 来进行多语言编码的特性从未被主流浏览器支持过）。 可是这个问题却的确是现实需要的，所以浏览器就各自想出了一些办法：
IE支持两种格式的混合版：filename="encoded_text" （这里采用的是百分号编码）。本来按照 RFC 2616 ，引号内的部分应当直接被当作内容，就算它“看起来像是编码后的字符串”；可是IE却会“自动”对这样的文件名进行解码——前提是该文件名必须有一个不会被编码的后缀名（即正常的英文字母后缀名）！
其他一些浏览器则支持一种更为粗暴的方式——允许在 filename="TEXT" 中直接使用 UTF-8 编码的字符串！
这两类浏览器的行为是彼此互不兼容的。所以你可以判断 UA 然后对IE使用前一种办法，其他浏览器使用后一种，这样便可以达到一般情况下能够 just work 的效果（ Discuz 就是这么做的）。不过对于 Opera 和 Safari ，这样做可能不一定有效。 时代在进步，2010年 RFC 5987 发布，正式规定了 HTTP Header 中多语言编码的处理方式，应当采用类似 MIME 扩展的 parameter*=charset'lang'value 的格式，但是其中 value 应根据 RFC 3986 Section 2.1 使用百分号进行编码，并且规定浏览器至少应该支持 ASCII 和 UTF-8 。随后，2011年 RFC 6266 发布，正式将 Content-Disposition 纳入 HTTP 标准，并再次强调了 RFC 5987 中多语言编码的方法，还给出了一个范例用于解决向后兼容的问题——就是我在一开始给出的例子：
Content-Disposition: attachment;
                      filename="encoded_text";
                      filename*=utf-8''encoded_text
在这个例子中，对于较新的 Firefox 、 Chrome 、 Opera 、 Safari 等浏览器，都支持新标准规定的 filename* ，并且会优先使用，所以尽管 filename=”encoded_text” 不被它们支持，仍然不会有问题；至于使用 UTF-8 只是因为它是标准中强制要求必须支持的。而对于旧版本的IE浏览器，它们无法识别后面的 filename* ，会自动忽略并使用旧的 filename 。这样一来就完美解决了多浏览器的多语言兼容问题，既不需要 UA 判断，也符合标准。 P.S. 为什么 PHP 要使用 rawurlencode() 函数呢？因为这才是真正符合 RFC 3986 的“百分号URL编码”，只是由于历史原因，之前先有了一个 urlencode() 函数用于实现 HTTP POST 中的类似的编码规则，故而只好用这么一个奇怪的名字。两者的区别在于前者会把空格编码为%20，而后者则会编码为+号。如果使用后者，那么IE6在下载带有空格的文件名时空格会变为加号。一般情况下，你是不会用到 urlencode() 这个函数的（ Discuz 某些版本中错误地使用它来进行文件名编码，从而导致空格变加号的BUG）。 via:Robot Shell