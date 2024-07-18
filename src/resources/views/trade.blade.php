<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trade</title>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js" ></script>

</head>
<body>

    <p>
        <b>Trade Numbers</b>
        <span id="trade-data"></span>
    </p>

    <script type="module" src="{{ asset('build/assets/app-b521bc98.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>

    
    <script>
    // console.log('Listening for NewTrade event');
    // Echo.channel('trades')
    //     .listen('NewTrade', (event) => {
    //         document.getElementById('trade-data').innerHTML = event.trade;
    //         console.log('Received NewTrade event:', event);
    //         console.log('Trade data:', event.trade);
    //     });

    </script>
    
</body>
</html>