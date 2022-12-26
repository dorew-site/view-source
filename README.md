# view-source
Một đoạn script nhỏ sử dụng CURL để xem mã nguồn HTML web.

# Hướng dẫn
> Thực ra ở trên mạng cũng có nhiều bài viết chia sẻ về phần này, nó rất dễ làm, chỉ cần dùng với curl, file, file_get_contents,.. và ở đây mình chọn CURL.

> Hầu hết tất cả các trang đều có thể xem nguồn HTML, nhưng một số trang thì không. Về vấn đề này, mình sử dụng **Cloudflare Workers**, như sau

1. Cần có 1 tài khoản cloudflare (tất nhiên rồi)
2. Đăng nhập vào bảng điều khiển -> tạo dự án mới với **Workers** -> dán code [ở đây](https://github.com/dorew-site/view-source/blob/main/cloudflare/config-workers.js) + deploy & save -> lấy link của worker page (dạng: **https://SITE_NAME.workers.dev**).

```js
async function gatherResponse(response) {
    const { headers } = response

    return {
        body: await response.body,
        extra: {
            status: response.status,
            statusText: response.statusText,
            headers: headers
        }
    }
}


async function handleRequest(request) {
    const { searchParams } = new URL(request.url)

    const baseHost = searchParams.get('url')

    const proxyRequest = new Request(baseHost, {
        method: request.method,
        headers: request.headers,
        cf: {
            cacheTtl: 10,
            cacheEverything: true
        }
    })

    const response = await fetch(proxyRequest)
    const results = await gatherResponse(response)
    return new Response(results.body, results.extra)
}


addEventListener("fetch", event => {
    return event.respondWith(handleRequest(event.request))
})
```

3. Chỉnh sửa tệp [function.php](https://github.com/dorew-site/view-source/blob/main/function.php), tìm **https://SITE_NAME.workers.dev** thành link worker page đã lấy được ở trên.
4. Chèn tệp **function.php** vào nơi muốn sử dụng. Ví dụ:

```php
<?php require_once 'function.php'; ?>
<textarea>
  <?php echo return_curl('https://dorew.gq'); ?>
</textarea>
```
