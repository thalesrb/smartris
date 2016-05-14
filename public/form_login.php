
<link href="http://www.gamepolis.com.br/css/login.css" rel="stylesheet">

<script src="http://www.gamepolis.com.br/plugins/modernizr.js"></script>
<script src="http://www.gamepolis.com.br/plugins/respond.js"></script>
<script src="http://www.gamepolis.com.br/plugins/icheck.js"></script>
<script src="http://www.gamepolis.com.br/plugins/placeholders.js"></script>
<script src="http://www.gamepolis.com.br/plugins/waypoints.js"></script>

<div class="eternity-form scroll-animations-activated">

    <div class="tab-content">
        <div class="tab-pane active" style='padding-top: 50px;' id="login">
            <div class="login-form-section">
                <div class="login-content  animated bounceIn" data-animation="bounceIn">
                    <form method='POST' action='login/'>
                        <div class="section-title">
                            <h3>Entrar no sistema</h3>
                            <p>É preciso estar logado para usar o sistema</p>
                        </div>
                        <div class="textbox-wrap">
                            <div class="input-group">
                                <span class="input-group-addon "><i class="fa fa-user"></i></span> <input required="required" class="form-control" placeholder="Usuário" type="text" name="cmp_login" value="Usuário">
                            </div>
                        </div>
                        <div class="textbox-wrap">
                            <div class="input-group">
                                <span class="input-group-addon "><i class="fa fa-key"></i></span> <input required="required" class="form-control has-error" placeholder="Senha" type="password" name="cmp_senha" value="Senha">
                            </div>
                        </div>
                        <div class="login-form-action clearfix">
                            <div class="checkbox pull-left">
                                <div class="custom-checkbox">
                                    <div class="icheckbox_square-blue checked" style="position: relative;">
                                        <input type="checkbox" name="iCheck" checked="" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 0px none; opacity: 0;">
                                        <ins class="iCheck-helper" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; background: none repeat scroll 0% 0% rgb(255, 255, 255); border: 0px none; opacity: 0;"></ins>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success pull-right">
                                Entrar &nbsp;<i class="glyphicon glyphicon-chevron-right"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
function Valida()
{
	var senha = $('#cmp_senha').val();
	var senha2 = $('#cmp_senha2').val();

	if (senha != senha2)
		{
		alert("As senhas digitadas não são iguais");
		return false;
		}

	$('form#frm_cadastro').submit();
}

function isEmail(s)
{
    var reEmail = /^[a-zA-Z0-9_!#$%&'*+\/=?^`{|}~-]+(\.[a-zA-Z0-9_!#$%&'*+\/=?^`{|}~-]+)*@(([a-zA-Z0-9-]+\.)+[A-Za-z]{2,6}|\[0-9{1,3}(\.0-9{1,3}){3}\])$/;
    if (isNull(s))
    return false;

    return reEmail.test(s);
}
</script>



<script type="text/javascript">
    $(function () {
        $('#login a').click(function (e) {
            e.preventDefault()
            $('a[href="' + $(this).attr('href') + '"]').tab('show');
        });

        $('#registrar a').click(function (e) {
            e.preventDefault()
            $('a[href="' + $(this).attr('href') + '"]').tab('show');
        });

        var hash = window.location.hash;
        hash && $('ul.nav a[href="' + hash + '"]').tab('show');

        $('.nav-tabs a').click(function (e) {
            $(this).tab('show');
            var scrollmem = $('body').scrollTop();
            window.location.hash = this.hash;
            $('html,body').scrollTop(scrollmem);
        });

        $("input").iCheck({
            checkboxClass: 'icheckbox_square-blue',
            increaseArea: '20%' // optional
        });
        $(".dark input").iCheck({
            checkboxClass: 'icheckbox_polaris',
            increaseArea: '20%' // optional
        });
        $(".form-control").focus(function () {
            $(this).closest(".textbox-wrap").addClass("focused");
        }).blur(function () {
            $(this).closest(".textbox-wrap").removeClass("focused");
        });

        //On Scroll Animations


        if ($(window).width() >= 968 && !Modernizr.touch && Modernizr.cssanimations) {

            $("body").addClass("scroll-animations-activated");
            $('[data-animation-delay]').each(function () {
                var animationDelay = $(this).data("animation-delay");
                $(this).css({
                    "-webkit-animation-delay": animationDelay,
                    "-moz-animation-delay": animationDelay,
                    "-o-animation-delay": animationDelay,
                    "-ms-animation-delay": animationDelay,
                    "animation-delay": animationDelay
                });

            });
            $('[data-animation]').waypoint(function (direction) {
                if (direction == "down") {
                    $(this).addClass("animated " + $(this).data("animation"));

                }
            }, {
                offset: '90%'
            }).waypoint(function (direction) {
                if (direction == "up") {
                    $(this).removeClass("animated " + $(this).data("animation"));

                }
            }, {
                offset: $(window).height() + 1
            });
        }

        //End On Scroll Animations


        $(".main-nav a[href]").click(function () {
            var scrollElm = $(this).attr("href");

            $("html,body").animate({ scrollTop: $(scrollElm).offset().top }, 500);

            $(".main-nav a[href]").removeClass("active");
            $(this).addClass("active");
        });

        if ($(window).width() > 1000 && !Modernizr.touch) {
            var options = {
                $menu: ".main-nav",
                menuSelector: 'a',
                panelSelector: 'section',
                namespace: '.panelSnap',
                onSnapStart: function () { },
                onSnapFinish: function ($target) {
                    $target.find('input:first').focus();
                },
                directionThreshold: 50,
                slideSpeed: 200
            };
        }

        $(".colorBg a[href]").click(function () {
            var scrollElm = $(this).attr("href");

            $("html,body").animate({ scrollTop: $(scrollElm).offset().top }, 500);

            return false;
        });

    });
</script>
