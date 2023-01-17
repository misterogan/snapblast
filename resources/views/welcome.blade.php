<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{csrf_token()}}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600&display=swap" rel="stylesheet">
{{--        <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">--}}
        </link>
        <link rel="stylesheet" href="{{mix('/css/app.css')}}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        <title>Snapblast</title>
    </head>
    <body class="antialiased">
        <div id="app">
            <app></app>
        </div>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <script type="text/javascript" src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script>
            // $(function () {
            //     $('input[name="daterange"]').daterangepicker({
            //         opens: 'left'
            //     }, function (start, end, label) {
            //         console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            //     });
            // });
        </script>
        <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
        <script defer src="{{ mix('js/app.js') }}"></script>
        <script>
            jQuery(document).ready(function($) {
                $(window).scroll(function () {
                    if ($(this).scrollTop() > 30) {
                        $('.page-title').fadeOut();
                    } else {
                        $('.page-title').fadeIn();
                    }
                });
                $.fn.dataTable.ext.errMode = 'throw';
            })
            $(document).on('click','.detail-menu ul > li', function (){
                $('.detail-menu ul > li').removeClass('active');
                $(this).toggleClass('active')
            })
        </script>
    </body>
</html>
