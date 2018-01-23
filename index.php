<?php

/**
 * 1. Дан каталог с подкаталогами, содержащими файлы с разными именами.
 * Также имеется файл main_list_name.txt с именами файлов.
 * Требуется вывести имена и пути к файлам в каталоге,
 * имена которых отличаются от списка с main_list_name.txt на 10% и более.
 */


function debug(array $a)
{
    echo '<pre>';
    print_r($a);
    echo '</pre>';
}


/**
 * Расчет отличия сзщк в файлах, ибо similar_text - показывает ахинею ( я надеялся, а в итоге мозг вынес!!!)
 */
/**
 * @param $a
 * @param $b
 * @return float|int
 */
function percent($a, $b)
{
    $a = strlen($a);
    $b = strlen($b);
    if ($a > $b) {
        return 100 - ($b * 100 / $a);

    } elseif ($b > $a) {
        return 100 - ($a * 100 / $b);
    } else {
        return 0;
    }
}

/**
 * @param $path
 * @param $name
 * @return bool
 */
function fileFind($path, $name)
{

    $dir = array_diff(scandir($path), [".", "..", ".DS_Store"]);
    foreach ($dir as $value) {
        if (!is_dir($path . "/" . $value)) {
            $arr = explode('.', $value);


            if (preg_match("~$arr[0]~", $name)) {

                $percent = percent($arr[0], $name);
                if ($percent >= 10) {
                    $arr = [
                        name=>$name,
                        percent=>$percent,
                        path=>$path . "/" . implode('.', $arr)

                    ];

                    return $arr;
                }

            }


        } else {
            $res = fileFind($path . "/" . $value, $name);
            if ($res)
                return $res;
        }
    }
    return false;
}


/**
 * @param $file
 * @return array
 */
function names($file)
{
    $str = file_get_contents($file);
    $arrName = explode(',', $str);
    $arr = [];
    foreach ($arrName as $name) {
        $arr[] = array_shift(explode(".", $name));

    }
    return $arr;
}

/**
 * @param $path
 * @param $file
 * @return array
 */
function changePath($path, $file)
{
    $arr = [];
    $names = names($file);
    foreach ($names as $name) {
        $arr[] = fileFind($path, $name);
    }
    return $arr;
}

$res = changePath("main_dir", "main_list_name.txt");

$res = array_diff($res,['',null]);
?>

<ol>

    <?php foreach ($res as $value): ?>

    <li> Имя файла: <?=$value['name']?>, Путь к файлу: <?= $value['path'] ?>, Отличие: <?=$value['percent'] ?> % </li>

    <?php endforeach; ?>

</ol>
