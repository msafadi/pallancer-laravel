require('./bootstrap');

window.Echo.private('orders')
    .listen('.order.created', function (data) {
        alert(JSON.stringify(data));
        console.log(data);
    })
