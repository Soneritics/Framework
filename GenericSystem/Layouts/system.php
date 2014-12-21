<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tandenborstel.com Ordersysteem</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/font-awesome.min.css" rel="stylesheet">
    <link href="/css/layout.css" rel="stylesheet">
  </head>
  <body>
    <!-- Main header -->
    <div id="header">
        <nav class="navbar navbar-default" role="navigation">
            <div>
                <a class="navbar-brand navbar-brand-center" href="/">T<span>an</span>d<span>en</span>b<span>orstel</span>.com</a>
            </div>

            <ul class="nav navbar-nav" id="enlarge-navbar">
                <li class="collapse-navbar"><a href="javascript:;" onclick="$('#navbar-collapse').slideToggle(500);"><span class="fa fa-arrows-v"></span></a></li>
            </ul>

            <div id="navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="/"><span class="fa fa-desktop"></span> <span class="txt">Orders</span></a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="fa fa-cog"></span><span class="txt"> Instellingen</span>
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="menu">
                                <ul>
                                    <li><a href="#"><span class="fa fa-cog"></span>Systeem</a></li>
                                    <li><a href="#"><span class="fa fa-users"></span>Gebruikers</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="logout"><a href="/"><span class="fa fa-sign-out"></span><span class="txt"> Uitloggen</span></a></li>
                </ul>
            </div>
        </nav>
    </div>
    <!-- /Main header -->

    <!-- Page header -->
    <div class="heading">
        <h3>Bestellingen</h3>

        <div class="search">
            <form id="searchform" action="search.html">
                <input type="text" id="tipue_search_input" class="top-search uniform-input text" placeholder="Search here ...">
            </form>
        </div>
    </div>
    <!-- /Page header -->

    <!-- Content -->
    <div class="orderlines container-fluid">
        <div class="row" onclick="$(this).find('input[type=checkbox]').click();">
            <div class="col-xs-4">Hoi</div>
            <div class="col-xs-4">Hoi</div>
            <div class="col-xs-3">Hoi</div>
            <div class="col-xs-1"><input type="checkbox"></div>
        </div>
    </div>
    <!-- /Content -->

    <!-- Page buttons -->
    <ul class="pagination">
        <li class="disabled"><a href="#">&laquo;</a></li>
        <li class="active"><a href="#">1</a></li>
        <li><a href="#">2</a></li>
        <li><a href="#">3</a></li>
        <li><a href="#">4</a></li>
        <li><a href="#">5</a></li>
        <li><a href="#">&raquo;</a></li>
    </ul>
    <!-- Page buttons -->

    <!-- Top button -->
    <ul class="pagination" id="back-to-top">
        <li><a href="#top"><span class="fa fa-sort-up"></span></a></li>
    </ul>
    <!-- /Top button -->

    <script src="/js/jquery-1.11.1.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/responsive.js"></script>
  </body>
</html>