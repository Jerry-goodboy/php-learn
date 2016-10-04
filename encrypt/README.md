### php encrypt
- Md5()
    - code
    ```PHP
    $str = 'dafdadsajlkjlejwlf';
    $str_md5 = md5(md5($str,true));
    // better more md5()
    ```
- Crypt()
    - code
    ```PHP
    $str = 'dafdadsajlkjlejwlf';
    $salt_des = 'tw';
    $salt_md5 = '$1$max eight$';
    $str_crypt_des = crypt($str,$salt_des);
    $str_crypt_md5 = crypt($str,$salt_md5);
    ```
- Sha1()
    - code
    ```PHP
    $str = 'dafdadsajlkjlejwlf';
    $str_sha1 = sha1(sha1($str,true));
    // $str_sha1 = sha1(md5($str,true));
    // better more sha1()
    ```
- URL encode
    - code
    ```PHP
    $str = 'list.php?req=dsfssd';
    $str_urlencode = urlencode($str);
    urldecode($str_urlencode);
    // rawurlencode()
    // rawurldecode()
    // blank space character encode %20, it's the only diff with urlencode() which encode +
    ```
- Base64 encode
    - code
    ```PHP
    base64_encode();
    base64_decode();
    // <img src="data:image/jpg;base64,base64_encode_string" alt="">
    ```

### 常见的加密技术
- 单项散列加密技术
    - MD5（Message Digest Algorithm 5）：是RSA数据安全公司开发的一种单向散列算法，MD5被广泛使用，可以用来把不同长度的数据块进行暗码运算成一个128位的数值。
    - SHA（Secure Hash Algorithm）这是一种较新的散列算法，可以对任意长度的数据运算生成一个160位的数值。
    - MAC（Message Authentication Code）：消息认证代码，是一种使用密钥的单向函数，可以用它们在系统上或用户之间认证文件或消息，常见的是HMAC（用于消息认证的密钥散列算法）。
    - CRC（Cyclic Redundancy Check）：循环冗余校验码，CRC校验由于实现简单，检错能力强，被广泛使用在各种数据校验应用中。占用系统资源少，用软硬件均能实现，是进行数据传输差错检测地一种很好的手段（CRC 并不是严格意义上的散列算法，但它的作用与散列算法大致相同，所以归于此类）。
- 对称加密技术
    - DES算法
    - 3DES算法
    - TDEA算法
    - Blowfish算法
    - RC5算法
    - IDEA算法
- 非对称加密技术
    - RSA
    - Elgamal
    - 背包算法
    - Rabin
    - D-H
    - ECC（椭圆曲线加密算法）

