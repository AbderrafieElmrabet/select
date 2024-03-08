<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <form method="POST">
    <input placeholder="enter database name" type="text" name="database">
    <input placeholder="enter table name" type="text" name="table">
    <input type="submit">
  </form>
  <table>
    <?php
    echo "<table style='border:1px solid;'>";
    echo "<tr><th>username</th><th>email</th><th>password</th></tr>";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $database = $_POST["database"];
      $table = $_POST["table"];
      class TableRows extends RecursiveIteratorIterator
      {
        function __construct($it)
        {
          parent::__construct($it, self::LEAVES_ONLY);
        }
        function current()
        {
          return "<td style='width: 150px; border: 1px solid black;'>"
            . parent::current() . "</td>";
        }
        function beginChildren()
        {
          echo "<tr>";
        }
        function endChildren()
        {
          echo "<tr>" . "\n";
        }
      }

      try {
        $connect = new PDO("mysql:host=localhost;dbname=$database", "root", "");
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $statement = $connect->prepare("SELECT username, password FROM $table");
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);

        foreach (new TableRows(new RecursiveArrayIterator($statement->fetchAll())) as $k => $v) {
          echo $v;
        }
      } catch (PDOException $e) {
        echo "a probleme happened" . " " . $e->getMessage();
      }
    }
    ?>
  </table>
</body>

</html>