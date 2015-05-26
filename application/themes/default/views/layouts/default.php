<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $template['title']; ?></title>
        <?= meta('Content-type', 'text/html; charset=utf-8', 'equiv'); // Note the third parameter. Can be "equiv" or "name" ?>
        <!-- We're minifying and combining all the CSS -->
        <link href="<?= $theme_path ?>workless/css/minified.css.php" rel="stylesheet" />
        <link href="<?= $theme_path ?>css/jquery.gritter.css" rel="stylesheet" />
        <link href="<?= $theme_path ?>css/number-polyfill.css" rel="stylesheet" />
        <link href="<?= $theme_path ?>framewarp/framewarp.css" rel="stylesheet" />


        <!-- All JavaScript at the bottom, except modernizr -->
        <script type="text/javascript" src="<?= $theme_path ?>workless/js/modernizr.js" ></script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" ></script>

        <script>window.jQuery || document.write('<script src="<?= $theme_path ?>workless/js/jquery.js"><\/script>');</script>
        <script src="<?= $theme_path ?>workless/js/plugins.js"></script>
        <script src="<?= $theme_path ?>workless/js/application.js"></script>
        <script src="<?= $theme_path ?>js/jquery.gritter.min.js"></script>
        <script src="<?= $theme_path ?>js/number-polyfill.js"></script>
        <script src="<?= $theme_path ?>framewarp/framewarp.js"></script>

        <script src="<?= $theme_path ?>js/main.js"></script>
        <?php echo $template['metadata']; ?>

        <script>
            var base_url = '<?= base_url() ?>';
        </script>

    </head>
    <body class="noise">

        <section class="boxed">
            <section id="main" >
                <h1><?php echo $template['title']; ?></h1>
                <?php echo $template['body']; ?>
            </section>

            <footer class="muted" >
                <p>
                    <small><a href="<?= base_url('main') ?>">accueil</a></small> -
                    <small><a href="<?= base_url('admin') ?>">admin</a></small>
                </p>
                <p><small>Page rendered in <strong>{elapsed_time}</strong> seconds</small></p>
                <?php if(!empty($list_elapsed_times)): ?>
                    <?php foreach($list_elapsed_times as $key => $a_time): ?>
                        <p><small><?= $key ?> : </small> <em><?= $a_time ?></em> seconds </p>
                    <?php endforeach; ?>
                <?php endif; ?>
            </footer>
        </section>

    </body>

</html>