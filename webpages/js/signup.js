    document.addEventListener("DOMContentLoaded", function () {

      // account type selection functionality
      const accountTypeRadios = document.querySelectorAll('.account-type-radio');
      const businessNameField = document.getElementById('business-name-field');
      const businessNameInput = document.getElementById('business_name');

      function updateAccountType(){
        const selectedType = document.querySelector('.account-type-radio:checked').value;

        accountTypeRadios.forEach(radio => {
          const option = radio.closest('.account-type-option');
           const radioDot = option.querySelector('.radio-dot');

           if(radio.checked){
            option.classList.add('border-brand', 'bg-blue-50');
            option.classList.remove('border-gray-200');
            radioDot.classList.remove('hidden');
           }else{
             option.classList.remove('border-brand', 'bg-blue-50');
            option.classList.add('border-gray-200');
            radioDot.classList.add('hidden');
            
           }

        });
        //show/hide business name field for retailers
        if(selectedType === 'Retailer'){
          businessNameField.classList.remove('hidden');
          businessNameInput.required = true;
        }else{
          businessNameField.classList.add('hidden');
          businessNameInput.required = false;
          businessNameInput.value = '';

        }
      }
      //add event listener for account type selection
      accountTypeRadios.forEach(radio => {
        radio.addEventListener('change', updateAccountType);
      });
      //account type initialized on page load
      updateAccountType();
        // form submission logic
      document.getElementById("registerForm").addEventListener("submit", function (event) {
        event.preventDefault();
        //getting the selected account type
        const selectedAccountType = document.querySelector('.account-type-radio:checked').value;

        var payload = {
          type: "Register",
          name: document.getElementById("name").value,
          surname: document.getElementById("surname").value,
          email: document.getElementById("email").value,
          password: document.getElementById("password").value,
          user_type: document.getElementById("user_type").value,
          account_type: selectedAccountType,
          business_name:selectedAccountType === 'vendor' ? document.getElementById("business_name").value : null
        };

        var xhr = new XMLHttpRequest();
        xhr.open("POST", "signupapi.php", true);
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
                  alert("Registration failed: " + JSON.stringify(response));
                }
              } catch (e) {
                alert("Invalid response from server.");
              }
            } else {
              alert("Request failed. Status: " + xhr.status);
            }
          }
        };

        xhr.send(JSON.stringify(payload));
      });
    });