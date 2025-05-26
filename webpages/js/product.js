document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById("productForm");

    form.addEventListener("submit", function(event){
        event.preventDefault(); 
        const productName = form.querySelector('input[placeholder="Enter product name"]').value;
        const description = form.querySelector('textarea[placeholder="Enter detailed product description"]') ? form.querySelector('textarea[placeholder="Enter detailed product description"]').value : "";
        const brand = form.querySelector('input[placeholder="Brand"]') ? form.querySelector('input[placeholder="Brand"]').value : "Unknown";
        const imgInput = form.querySelector('#productImage');
        const imgReference = imgInput && imgInput.files.length > 0 ? imgInput.files[0].name : "";
        const categorySelect = form.querySelector('select');
        const categoryID = categorySelect && categorySelect.value ? categorySelect.value : 1;
        const priceInput = form.querySelector('input[type="number"]');
        const price = priceInput ? priceInput.value : "";

        const ApiKey = localStorage.getItem("apiKey");
        if (!ApiKey) {
            console.error("ApiKey not found in localStorage.");
            return; 
        }
        // const ApiKey = "2vlzqHAldgmmkaS2n1Efwz1Y2Z68u8Ai";

        const productInformation = {
            type: "addProduct",
            ProductName: productName,
            Description: description,
            Brand: brand,
            IMG_Reference: imgReference,
            CategoryID: categoryID,
            Price: price,

        }

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "/PA_221_FRONTEND-LINKING/api.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function(){
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.status === "success") {
                    alert("Product added successfully!");
                    form.reset();
                } else {
                    alert("Error adding product: " + response.message);
                }
            } else if (xhr.readyState === 4) {
                alert("Failed to connect to the server.");
            }
        };

        console.log(productInformation);

        xhr.send(JSON.stringify(productInformation));
    });
});