document.addEventListener("DOMContentLoaded", function () {

      // account type selection functionality
      const accountTypeRadios = document.querySelectorAll('input[name="account_type"]');
      const retailerFields = document.querySelectorAll('.retailer-fields');
      const businessNameInput = document.getElementById('business_name');
      const siteReferenceInput = document.getElementById('site_reference');
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
          siteReferenceInput.required = true;
          verificationDocument.required = true;
        }else{
         retailerFields.forEach(field=>{
            field.style.display = 'none';

          });
        
          businessNameInput.required = false;
          siteReferenceInput.required = false;
          verificationDocument.required = false;

          businessNameInput.value='';
          siteReferenceInput.value='';
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
        const firstName = document.getElementById("firstname").value;
        const lastName = document.getElementById("lastname").value;

        
        

        const confirmpassword = document.getElementById("confirm-password").value;
        const passworduser = document.getElementById("password").value;

        if (passworduser !== confirmpassword) {
          alert("Passwords do not match. Please try again.");
          return;
        }
        
        var selectedAccountType = document.querySelector('input[name="account_type"]:checked').value;
        var payload = {};
        if (selectedAccountType=="customer"){
          selectedAccountType="Standard";
          payload = {
            type: "Signup",
            name: firstName,
            surname: lastName,
            email: document.getElementById("email").value,
            password: document.getElementById("password").value,
            user_type: selectedAccountType
          };

        }
        
        else{
          selectedAccountType="Retailer";
          payload = {
            type: "Signup",
            name: firstName,
            surname: lastName,
            email: document.getElementById("email").value,
            password: document.getElementById("password").value,
            user_type: selectedAccountType,
            RetailerName: document.getElementById("business_name").value,
            SiteReference: document.getElementById("site_reference").value
          };
        }



        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../api/api.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function () {
          if (xhr.readyState === 4) {
            if (xhr.status === 200) {
              try {

              alert("Login successful!");


                var response = JSON.parse(xhr.responseText);
                if (response.status === "success") {
                  if (selectedAccountType === "Retailer") {
                    localStorage.setItem("apiKey", response.data.apikey);
                    localStorage.setItem("userType", selectedAccountType);
                    localStorage.setItem("email", document.getElementById("email").value);
                    localStorage.setItem("RetailerName", document.getElementById("business_name").value);

                    alert("Registration successful!");
                    window.location.href = "index.php";
                  }

                  else if (selectedAccountType === "Standard") {
                      localStorage.setItem("apiKey", response.data.apikey);
                      localStorage.setItem("userType", selectedAccountType);
                      localStorage.setItem("email", document.getElementById("email").value);
                      localStorage.setItem("name", firstName);
                      localStorage.setItem("surname", lastName);

                      alert("Registration successful!");
                    window.location.href = "index.php";
                  }
                  
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