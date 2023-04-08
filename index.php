<?php
$page_name = "Localhost";
$home = "https://localhost";
$db = new SQLite3('ignore.db');
$db->exec('CREATE TABLE IF NOT EXISTS ignore (id INTEGER PRIMARY KEY, name TEXT)');

if(isset($_GET['ignore'])){
    $db->exec('INSERT INTO ignore (name) VALUES ("' . $_GET['ignore'] . '")');
    header('Location: ./');
}
if(isset($_GET['keep'])){
    $db->exec('DELETE FROM ignore WHERE name = "' . $_GET['keep'] . '"');
    header('Location: ./');
}
//aller chercher tous les dossiers ignorés
$ignored = $db->query('SELECT name FROM ignore');
$ignoredList = array();
while ($row = $ignored->fetchArray(SQLITE3_ASSOC)) {
    // ajouter chaque ligne dans le tableau $ignoredList
    $ignoredList[] = $row['name'];
}

function getIcon($folder){
    $icon = "fas fa-folder";
    if(file_exists($folder . '/index.html') || file_exists($folder . '/index.php')) {
        $icon = "fas fa-globe";
    }
    if(file_exists($folder . '/bin/console')){
        $icon = "fab fa-symfony";
    }
    if(file_exists($folder . '/app/Console')){
        $icon = "fab fa-laravel";
    }
    return $icon;
}
function getLink($folder){
    $link = "/" . $folder;
    //si on est actuellement sur localhost
    if($_SERVER['HTTP_HOST'] == "localhost"){
        if(file_exists($folder . '/public/index.php')
            || file_exists($folder . '/index.html')
            || file_exists($folder . '/index.php')){
            $link = "https://" . $folder . ".test";
        }
    }
    return $link;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
          crossorigin="anonymous"
          referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,600;1,400&display=swap" rel="stylesheet">
    <script
            src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/gsap.min.js"
            integrity="sha512-cOH8ndwGgPo+K7pTvMrqYbmI8u8k6Sho3js0gOqVWTmQMlLIi6TbqGWRTpf1ga8ci9H3iPsvDLr4X7xwhC/+DQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        *{
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background-color: whitesmoke;
        }

        header{
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
            padding: 20px;
            background-color: white;
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header .title {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        header i{
            font-size: 30px;
        }

        header a {
            text-decoration: none;
            color: black;
        }

        ul{
            padding: 20px;
            list-style: none;
            width: fit-content;

        }

        ul li {
            display: flex;
            align-items: center;
        }

        ul li>div{
            margin: 20px;
            padding: 10px 20px;
            background-color: white;
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
            border-radius: 20px;
            cursor: pointer;
        }

        ul li i.fa-cancel {
            opacity: 0;
            transition: opacity 0.2s ease-in-out;
            cursor: pointer;
        }

        ul li i.fa-cancel:hover {
            opacity: 1;
        }

        ul li a {
            text-decoration: none;
            color: black;
            font-size: 1rem;
            margin-left: 10px;
        }

        ul li i.fa-globe {
            color: #2da92a;
        }

        ul li i.fa-laravel {
            color: #f55247;
        }

        form {
            position: absolute;
            right: 0;
            bottom: 0;
            padding: 10px;
            margin: 10px;

        }

        h2 {
            border-bottom: 1px solid black;
        }
    </style>
    <title><?= $page_name ?></title>
</head>
<body>
<header>
    <div class="title">
        <i class="fas fa-network-wired" id="icon"></i>
        <h1><?= $page_name ?></h1>
    </div>
    <a href="<?= $home ?>">
        <i class="fas fa-home"></i>
    </a>
</header>
<ul class="keep">
    <h2>Found</h2>
    <?php
    $folders = glob('*', GLOB_ONLYDIR);
    foreach($folders as $folder){
        if(!($ignoredList && in_array($folder, $ignoredList))){
            echo '<li><i class="fas fa-cancel"></i><div><i class="' . getIcon($folder) . '"></i><a href="'
                . getLink($folder) . '">' . $folder . '</a></div></li>';
        }
    }
    ?>
</ul>
<form action="">
    <input type="checkbox" name="ignored" id="ignored">
    <label for="ignored">Ignored</label>
</form>
<?php if($ignoredList): ?>

    <ul class="ignore">
        <h2>Ignored</h2>
        <?php
        foreach($folders as $folder){
            if($ignoredList && in_array($folder, $ignoredList)){
                echo '<li><i class="fas fa-cancel"></i><div><i class="' . getIcon($folder) . '"></i><a href="'
                    . getLink($folder) . '">' . $folder . '</a></div></li>';
            }
        }
        ?>
    </ul>
<?php endif; ?>
<script>
    const ignored = document.querySelector('ul.ignore');
    gsap.set(".ignore", {opacity: 0, x: -100});
    document.querySelector('form input[name="ignored"]').addEventListener('change', (e) => {
        if(e.target.checked){
            ignored.style.display = 'block';
            gsap.set(".ignore", {opacity: 0, x: -100});
            gsap.to(".ignore",
                {
                    duration: .5,
                    opacity: 1,
                    x: 0,
                });
        } else {
            ignored.style.display = 'none';
        }
    });

    document.querySelectorAll('ul.keep i.fa-cancel').forEach((el) => {
        el.addEventListener('click', (e) => {
            e.preventDefault();
            const name = e.target.parentElement.querySelector('a').innerText;
            window.location.href = `./?ignore=${name}`;
        });
    });
    document.querySelectorAll('ul.ignore i.fa-cancel').forEach((el) => {
        el.addEventListener('click', (e) => {
            e.preventDefault();
            const name = e.target.parentElement.querySelector('a').innerText;
            // redirect to ./?ignore=NAME
            window.location.href = `./?keep=${name}`;
        });
    });
    gsap.set(".keep li", {opacity: 0, x: -100});
    window.onload = () => {
        gsap.to(".keep li",
            {
                duration: .5,
                opacity: 1,
                x: 0,
                stagger: 0.2,
            });
    };
</script>
</body>
</html>
