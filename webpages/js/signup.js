document.addEventListener("DOMContentLoaded", function () {

      // account type selection functionality
      const accountTypeRadios = document.querySelectorAll('input[name="account_type"]');
      const retailerFields = document.querySelectorAll('.retailer-fields');
      const businessNameInput = document.getElementById('business_name');
      const registrationNumberInput = document.getElementById('registration_number');
      const verificationDocument = document.getElementById('verification_document');



      function updateAccountType(){
        const selectedType = document.querySelector('input[name="account_type"]:checked').value;

        accountTypeRadios.forEach(radio => {
          const label = radio.nextElementSibling;
           

           if(radio.checked){
            label.classList.add('selected');
           }else{
            label.classList.remove('selected');
            
           }

        });
        //show/hide fields meant  for retailers
        if(selectedType === 'retailer'){
          retailerFields.forEach(field=>{
            field.style.display = 'block';

          });
        
          businessNameInput.required = true;
          registrationNumberInput.required = true;
          verificationDocument.required = true;
        }else{
         retailerFields.forEach(field=>{
            field.style.display = 'none';

          });
        
          businessNameInput.required = false;
          registrationNumberInput.required = false;
          verificationDocument.required = false;

          businessNameInput.value='';
          registrationNumberInput.value='';
          verificationDocument.value='';


        }
      }
      //add event listener for account type selection
      accountTypeRadios.forEach(radio => {
        radio.addEventListener('change', updateAccountType);
      });
      //account type initialized on page load
      updateAccountType();

      const fileUploadArea = document.getElementById('fileUploadArea');
      const verificationStatus = document.getElementById('verificationStatus');

      if(fileUploadArea){
        fileUploadArea.addEventListener('click', function(){
          verificationDocument.click();
        } );

        verificationDocument.addEventListener('change', function(){
          const file = this.files[0];
          if(file) {
            if(file.size > 5*1024*1024){
              alert('File size must be less than 5MB');
              this.value= '';
              return;
            }

            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
            if(!allowedTypes.includes(file.type)){
              alert('Please upload a PDF, JPG, or PNG file');
              this.value='';
              return;
            }

            fileUploadArea.querySelector('.upload-text').textContent = file.name;
            fileUploadArea.querySelector('.upload-subtext').textContent = 'File selected - Click to change';

            verificationStatus.style.display = 'block';
            verificationStatus.textContent='Document uploaded successfully. Verification pending.'

          }
        });
      }

        // form submission logic
      document.getElementById("signup-form").addEventListener("submit", function (event) {
        event.preventDefault();
        // Get full name and split into first and last name
        const fullName = document.getElementById("fullname").value.trim();
        let name = "";
        let surname = "";
        if (fullName.includes(" ")) {
          name = fullName.substring(0, fullName.lastIndexOf(" ")).trim();
          surname = fullName.substring(fullName.lastIndexOf(" ") + 1).trim();
        } else {
          name = fullName;
          surname = "";
        }
        var selectedAccountType = document.querySelector('input[name="account_type"]:checked').value;
        if (selectedAccountType=="customer"){
          selectedAccountType="Standard";

        }else{
          selectedAccountType="Retailer";
        }
        var payload = {
          type: "Signup",
          name: name,
          surname: surname,
          email: document.getElementById("email").value,
          password: document.getElementById("password").value,
          user_type: selectedAccountType
        };
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../api/api.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function () {
          if (xhr.readyState === 4) {
            if (xhr.status === 200) {
              try {
                var response = JSON.parse(xhr.responseText);
                if (response.status === "success") {
                  localStorage.setItem("apiKey", response.data.apikey);
                  alert("Registration successful!");
                  window.location.href = "index.php";
                } else {
                  alert("Registration failed: " + JSON.stringify(response.data));
                }
              } catch (e) {
                alert("Invalid response from server.");
                console.log(e);
              
              }
            } else {
              alert("Request failed. Status: " + xhr.status);
            }
          }
        };
        console.log(payload);
        xhr.send(JSON.stringify(payload));
      });
    });