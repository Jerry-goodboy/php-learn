### js怎么禁止手机页面长按保存图片
- 用div层，层的background-image设置为图片，而不用img标签。这样浏览器就不会调用保存图片的功能了。
- 利用CSS img {pointer-event:none;-webkit-user-select:none;-moz-user-select:none;user-select:none;}
