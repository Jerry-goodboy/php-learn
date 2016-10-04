### Implementation Rationale
- `file_put_contents()`
- php built-in output_buffering
    - `ob_start()`
    - `ob_get_contents()`
    - `ob_clean()`
    - `ob_get_clean()`

### how to trigger
- Add cache time on the page
    - code 
    ```PHP
    if(is_file('./staticize.html') && (time() - filemtime('./staticize.html')) < 300 ){
        require_once('./staticize.html');
    }else {
        // staticize html again
    }
    ```
- manually
    - add a button and access staticize.php manually
- crontab command using on linux
    - code 
    ```
    lcd@monster$ crontab -e
    ```
    ```bash
    */1 * * * * php staticize.php
    # staticize html once in every minute
    ```

### how to staticize part of html to dynamic load contents
- Ajax is the answer

### Pseudo staticize
- php path_info
    - code 
    ```PHP
    preg_match('/^\/(\d+)\/(\d+).html/', $_SERVER['PATH_INFO'], $query_string_arr);

    $query_string_1 = $query_string_arr[1];
    $query_string_2 = $query_string_arr[2];
    ```
- apache rewrite (httpd.conf && httpd-vhosts.conf)
    - LoadModule rewrite module modules/mod_rewrite.so
    - Include conf/extra/httpd-vhosts.conf
    - httpd-vhosts.conf code
    ```
    <VirtualHost 127.0.0.1:80>
        DocumentRoot "/var/web/staticize"
        ServerName staticize.com

        RewriteEngine on
        RewriteCond %{DOCUMENT_ROOT}%{REQUEST_FILENAME} !-d
        RewriteCond %{DOCUMENT_ROOT}%{REQUEST_FILENAME} !-f
        RewriteRule ^/detail/([0-9]*).html$ /detail.php?id=$1
    </VirtualHost>
    ```
- nginx rewrite 
    - code
    ```
    location / {
        if(!-e $request_filename){
            rewrite ^/detail/([0-9]*).html$ /detail.php?id=$1 last;
            break;
        }
    }
    ```