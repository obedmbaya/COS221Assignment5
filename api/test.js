var btnPopulate = document.getElementById("populateDB");

if (btnPopulate){
    btnPopulate.addEventListener("click", function(){
        getData(function(products){

            var data = {
                "products":products,
                "type" : "populateDB"
            }

            ajax(data, function(response){
                console.log(response);
                if (response.status == "success"){
                    console.log("Database populated successfully");
                    document.getElementById("json-output").innerHTML = JSON.stringify(response.data);
                } else {
                    console.log("Error populating database");
                }
            });

        });


    });
} else {
    console.error("Element with ID 'populateDB' not found.");
}

function getData(callback) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'data.json', true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                var data = JSON.parse(xhr.responseText);
                console.log(data);
                callback(data);
            } else {
                console.error("Failed to fetch data. Status:", xhr.status);
            }
        }
    };
    xhr.send();
}

function ajax(data, callback){

    var req = new XMLHttpRequest();

    req.onreadystatechange = function() {
        if (req.readyState === 4) {
            if (req.status === 200) {
                console.log(req.responseText);
                var response = JSON.parse(req.responseText);
                callback(response);
            } else {
                console.error("Failed to send data. Status:", req.status);
            }
        }
    };

    req.open("POST", "api.php", true);
    req.setRequestHeader("Content-Type", "application/json");
    req.send(JSON.stringify(data));

}