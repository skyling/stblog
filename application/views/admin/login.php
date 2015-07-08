<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Foundation | Welcome</title>
    <link rel="stylesheet" href="<?=ST_FOUNDATION ?>/css/foundation.css" />
    <script src="<?=ST_FOUNDATION?>/js/vendor/modernizr.js"></script>
</head>
<body>

<div class="row">
    <div class="large-12 columns text-center">
        <h1>STBLOG 后台管理</h1>
    </div>
    <hr><br><br><br><br><br>
    <div class="panel large-8 large-offset-2 columns text-center">
        <?=form_open()?>
        <div class="large-8 large-offset-2 columns">
            <div class="row collapse">
                <div class="large-2 columns">
                    <span class="prefix">用户名
                </div>
                <div class="large-8 columns">
                    <input type="text" name="name" value="<?=set_value('name')?>" placeholder="请输入管理员用户名">
                </div>
                <div class="large-2 text-center  columns">
                    <?=form_error('name')?>
                </div>
            </div>
            <div class="row collapse">
                <div class="large-2 columns">
                    <span class="prefix">密码
                </div>
                <div class="large-8 columns">
                    <input type="password" name="password" value="<?=set_value('password')?>" placeholder="请输入登录密码">
                </div>
                <div class="large-2 text-center columns">
                    <?=form_error('password')?>
                </div>
            </div>
            <div class="row collapse">
                <div class=" large-10 columns">
                    <button type="submit" class="large-10 button success small">登录</button>
                </div>
            </div>
        </div>
        </from>
    </div>
</div>

<script src="<?=ST_FOUNDATION ?>/js/vendor/jquery.js"></script>
<script src="<?=ST_FOUNDATION ?>/js/foundation.min.js"></script>
<script>
    $(document).foundation();
</script>
</body>
</html>
