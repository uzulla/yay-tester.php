<?php
declare(strict_types = 1);
require __DIR__ . "/vendor/autoload.php";

if (
    $_SERVER['REMOTE_ADDR'] !== '127.0.0.1' ||
    !preg_match("/Development Server/", $_SERVER['SERVER_SOFTWARE'])
) {
    die('This is dangerous, only run with Builtin server and localhost access');
}

session_start();

$code = '';
$converted_code = '';
$output = '';

if (isset($_POST['code'])) {
    $code = $_POST['code'];
    if ($_POST['csrf'] !== $_SESSION['csrf']) {
        die('csrf');
    }

    try {
        $converted_code = yay_parse($code);

        try {
            ob_start();
            eval("?>" . $converted_code);
            $output = ob_get_clean();
        } catch (\Throwable $e) {
            $output = 'Execute ERROR at line:' . $e->getLine() . PHP_EOL;
            $output .= $e->getMessage();

        }

    } catch (\Yay\YayParseError $e) {
        $converted_code = 'Convert ERROR at line:' . $e->getLine() . PHP_EOL;
        $converted_code .= $e->getMessage();
    }
} else {
    $filename = (string)$_GET['filename'] ?? 'unless';
    if(!preg_match('/\A[a-z]{1,32}\z/u', $filename)) throw new \Exception('invalid filename');
    $code = file_get_contents("sample/{$filename}.php");
}

$_SESSION['csrf'] = $_SESSION['csrf'] ?? base64_encode(random_bytes(64));

?>

<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-1.12.3.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="ace_editor/ace.js" type="text/javascript" charset="utf-8"></script>
    <style type="text/css" media="screen">
        #code {
            width: 100%;
            height: 200px;
        }

        #converted_code, #output {
            width: 100%;
            height: 150px;
        }

        h2 {
            margin-top: 0px;
            margin-bottom: 0px;
        }
    </style>
    <script>
        var editor;
        $(function () {
            editor = ace.edit("code");
            editor.setTheme("ace/theme/twilight");
            editor.session.setMode("ace/mode/php");

            editor.commands.addCommand({
                name: 'run',
                bindKey: {win: 'Ctrl-Enter', mac: 'Command-Enter'},
                exec: function (editor) {
                    run();
                }
            });
            editor.focus();

            var output = ace.edit("output");

            var converted_code = ace.edit("converted_code");
            converted_code.setTheme("ace/theme/twilight");
            converted_code.session.setMode("ace/mode/php");
        });

        function run() {
            $("#code_textarea").val(editor.getValue());
            $("#form").submit();
        }

        function changeSample(e){
            var filename = $('option:selected', e.currentTarget).val();
            location.href = '/?filename='+filename;
        }
    </script>
</head>
<body>

<h2>input</h2>
<form method="post" id="form">
    <div id="code"><?php echo htmlspecialchars($code, ENT_QUOTES) ?></div>
    <textarea name="code" id="code_textarea" style="display:none"></textarea>
    <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['csrf'], ENT_QUOTES) ?>">
    <button type="button" onclick="run()">RUN (cmd|ctrl + enter)</button>
    <select onchange="changeSample(event);">
        <option>load sample code</option>
        <?php
        $filename_list = array_map(function($v){
            preg_match('|sample/([a-z]{1,32}).php|u', $v, $_);
            return $_[1];
        }, glob('sample/*.php'));
        foreach($filename_list as $filename){
            echo "<option>{$filename}</option>";
        }
        ?>
    </select>
</form>

<h2>preprocessed code</h2>
<div id="converted_code"><?php echo htmlspecialchars($converted_code, ENT_QUOTES) ?></div>

<h2>output</h2>
<div id="output"><?php echo htmlspecialchars($output, ENT_QUOTES) ?></div>

</body>
</html>
