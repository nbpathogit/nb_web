

<!DOCTYPE HTML>  
<html>  
    <head>  
        <title>  
            JQuery | Change the text of a span element 
        </title> 
          
        <script src =  
"https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"> 
        </script> 
    </head>  
      
    <body style = "text-align:center;" id = "body">  
          
        <h1 style = "color:green;" >  
            GeeksForGeeks  
        </h1>  
          
        <span id="GFG_Span" style = "font-size: 15px; font-weight: bold;">  
            This is text of Span element.  
        </span> 
          
        <br><br> 
          
        <button id="btn">  
            Change 
        </button> 
          
        <p id = "GFG_DOWN" style =  
            "color:green; font-size: 20px; font-weight: bold;"> 
        </p> 
        <br><br> 
        <span id="code2_2187" ondblclick="setCode2(2187)">5</span>
          
        <script> 
            $('#btn').on('click', function() { 
                let str1 = "#GFG_"+"Span";
                $(str1).text("New Span text content"); 
                $('#GFG_DOWN').text("Span content changed"); 
                    let billing_id = 2187;
                    let str_code2_billing_id = '#code2_'+billing_id;
                    alert('str_code2_billing_id::'+str_code2_billing_id);
                    let code2 = 7;
                    let newval = code2+'';
                    alert('newval::'+newval);
                    $(str_code2_billing_id).text('newval');
            });      
        </script>  
    </body>  
</html>    