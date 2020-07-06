require('./bootstrap');

window.Echo.private('orders')
    .listen('.order.created', function (data) {
        alert(JSON.stringify(data));
        console.log(data);
    });

window.Echo.private('App.User.' + userId)
    .notification(function(data) {
        alert(data.message);
    })
