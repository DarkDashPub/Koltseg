<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="utf-8" />

    <title>Roller</title>

</head>
<body>
<style>
.container
{
    text-align:center;
    width:200px;
    color:white;
    background: linear-gradient(90deg,black,red);
    border: 2px solid black;
	font-weight:bold;
}
    table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width:100%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
  
}

tr:nth-child(even) {
  background-color: #dddddd;
}

</style>
<form class="container">
	Költség tipusa:<br />
	<input type="radio" name="koltseg" value= "Szerviz"> Szerviz <br />
    <input type="radio" name="koltseg" value= "Tankolás"> Tankolás <br />
    <input type="radio" name="koltseg" value= "Egyéb"> Egyéb <br />
    Rövid leírás (opcionális): 
    <input type ="text" name = "input_koltseg_leiras"> <br />
    Összeg:
    <input type ="number" name = "input_koltseg_osszeg"> <br />
    Dátum:
    <input type ="date" name = "input_koltseg_datum"> <br />	
	<input type="hidden" name="action" value="cmd_insert_koltseg">
	<input type="submit" value="Költség felvétele">
</form>

<?php
   /* echo "<pre>";
    var_dump($_REQUEST);
    echo "</pre>"; */
    if(isset($_GET["action"]) and $_GET["action"]=="cmd_insert_koltseg")
    {
       // if(isset($_POST["input_koltseg_osszeg"]) and
           // is_numeric($_POST["input_koltseg_osszeg"])) //and
            //isset($_POST["input_koltseg_datum"]) and
            //!empty($_POST["input_koltseg_datum"]))
          //  {
                $felvetel = new autok();
                $felvetel->koltseg_felvetel(strval($_GET["koltseg"]),
                                                $_GET["input_koltseg_leiras"],
                                                 $_GET["input_koltseg_osszeg"],
                                                 $_GET["input_koltseg_datum"]);
          //  }
    }
    if(isset($_GET["action"]) and $_GET["action"]=="cmd_delete_koltseg"){
        if (isset($_GET["id"]) and
            is_numeric($_GET["id"])){
                $torles = new autok();
                echo $torles->koltseg_torles($_GET["id"]);
            }
    }
$autok = new autok();
$autok->koltseg_lista();

?>

</body>
</html>


<?php
    class autok
    {
        public $servername = "localhost:3306";
        public $username = "root";
        public $password = "";
        public $dbname = "koltsegvetes";
        public function __construct(){ self::kapcsolodas(); }
        public function __destruct(){ self::kapcsolatbontas();}
        public function kapcsolodas(){

            // Create connection
		$this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
		    // Check connection
        if ($this->conn->connect_error) 
        {
			die("Connection failed: " . $this->conn->connect_error);
	    }		
                                    }
    

    public function kapcsolatbontas(){
		$this->conn->close();
    }
    
    

    public function koltseg_felvetel($tipus,$leiras,$osszeg,$datum)
    {
        $this->sql = "INSERT INTO scenic
                                (
                                    id,
                                    koltseg_fajtaja,
                                    leiras,
                                    osszeg,
                                    datum
                                )
                                VALUES
                                (
                                    NULL,
                                    '$tipus',
                                    '$leiras',
                                    $osszeg,
                                    '$datum'
                                )";
            echo $this->sql;
        if($this->conn->query($this->sql))
        {
            return "<p>Sikeres felvétel!</p>";
        }
        else
        {
            return "<p>Sikertelen felvétel :(</p>";
        }
    }

    public function koltseg_torles($id){
		if ($id == "") return "<p>Sikertelen törlés, a id nem lehet üres!</p>";
		$this->sql = "DELETE FROM scenic
					  WHERE id = $id;
                ";
            
		if($this->conn->query($this->sql)){
			return "<p>Sikeres törlés!</p>";
		} else {
			return "<p>Sikertelen törlés!</p>";
		}
    }
    
    public function koltseg_lista()
    {
        $this->sql = "SELECT 
                        id,
                        koltseg_fajtaja,
                        leiras,
                        osszeg,
                        datum
                        FROM
                        scenic";
        $this->result = $this->conn->query($this->sql);
        if($this->result->num_rows > 0)
        {
             
            echo '<table>';
            echo "<tr>";
                echo "<th>ID </th>";
                echo "<th>Tipusa </th>";
                echo "<th>Leirás </th>";
                echo "<th>Összeg </th>";
                echo "<th>Dátum </th>";
                echo "<th> </th>";
            echo "</tr>";
            
            while($this->row = $this->result->fetch_assoc())
            {
                
               
                    echo "<tr>";
                        echo "<td>" .$this->row["id"] . "</td>";
                        echo "<td>" .$this->row["koltseg_fajtaja"] . "</td>";
                        echo "<td>" .$this->row["leiras"] . "</td>";
                        echo "<td>" .$this->row["osszeg"] . " Ft</td>";
                        echo "<td>" .$this->row["datum"] . "</td>";
                        echo "<td>";
                        echo "<form>";
                echo "	<input type='hidden' name='action' value='cmd_delete_koltseg'>";
                echo "	<input type='hidden' name='id' value='".$this->row["id"]."'>";
                echo "<input type='submit' value='törlés'>";
                echo "<br />";
                echo "</form>";
                echo "</td>";
                    echo "</tr>";
                
                        
              
                /* echo "id: " .$this->row["id"];
                echo " - tipusa: " .$this->row["koltseg_fajtaja"];
                echo " - leiras: " .$this->row["leiras"];
                echo " - osszeg: " .$this->row["osszeg"];
                echo " - datum: " .$this->row["datum"];*/
                
               
            }
            echo "</table>";

        }
        else 
        {
            echo "0 eredmény";
        }
    }
    }
?>