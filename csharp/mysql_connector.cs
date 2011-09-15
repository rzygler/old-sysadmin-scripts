using MySql.Data.MySqlClient; 

// uses mysql connector from mysql.com

class mysql 
{ 
  static void Main() 
  { 
    MySqlConnection conn; 
    string myConnectionString; 

    myConnectionString = "server=localhost;user id=test; pwd=test;database=world;"; 

    try 
    { 
      conn = new MySqlConnection(); 
      conn.ConnectionString = myConnectionString; 
      conn.Open(); 

      string sql = "SELECT Name, Continent FROM Country Limit 10"; 

      MySqlCommand cmd = new MySqlCommand(); 
      MySqlDataReader results; 
      cmd.CommandText = sql; 
      cmd.Connection = conn; 
      results = cmd.ExecuteReader(); 

      while (results.Read()) 
      { 
        //string f1 = results.GetString(0); 
        //int f2 = results.GetInt32(1); 
        System.Console.WriteLine(results.GetString(0) + ", " + results.GetString(1)); 
      } 

      results.Close(); 
      System.Console.ReadLine(); 
    } 
    catch (MySqlException ex) 
    { 
      System.Console.WriteLine("error" + ex.Message); 
      System.Console.ReadLine(); 
    } 
  } 
}

