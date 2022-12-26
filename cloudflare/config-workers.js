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
