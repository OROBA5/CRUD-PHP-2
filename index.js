document.addEventListener("DOMContentLoaded", function () {
    
  // Show/hide additional fields based on selected type
  document.getElementById("productType").addEventListener("change", function () {
    var selectedType = this.value;

    // Hide all dynamic fields
    document.getElementById("book-fields").classList.add("hidden");
    document.getElementById("dvd-fields").classList.add("hidden");
    document.getElementById("furniture-fields").classList.add("hidden");

    // Show fields based on the selected type
    if (selectedType === "3") {
      document.getElementById("book-fields").classList.remove("hidden");
    } else if (selectedType === "1") {
      document.getElementById("dvd-fields").classList.remove("hidden");
    } else if (selectedType === "2") {
      document.getElementById("furniture-fields").classList.remove("hidden");
    }
  });
  
  document.getElementById("sku").addEventListener("change", function () {
  var sku = this.value;
  var warningDiv = document.getElementById("warning");

  // Check if SKU is empty
  if (sku === "") {
    warningDiv.textContent = "SKU field is required";
    warningDiv.style.display = "block";
    return;
  }

  // Make an AJAX request to check if the SKU is already used
  fetch("http://localhost/scandiweb-api/actions/read.php")
  .then((response) => response.text()) // Parse response as text
  .then((data) => {
    if (data === '{"sku": "0"}') {
      // This is a response indicating no products found, so assume the SKU is available
      warningDiv.textContent = ""; // Clear the warning message
      warningDiv.style.display = "none";
    } else {
      // Parse the response as JSON
      const jsonData = JSON.parse(data);
      if (Array.isArray(jsonData)) {
        // This is actual product data, so proceed with SKU check
        var existingSkus = jsonData.map((product) => product.sku);
        if (existingSkus.includes(sku)) {
          // SKU is already taken, show a warning
          warningDiv.textContent = "SKU is already used for a different product";
          warningDiv.style.display = "block";
        } else {
          // SKU is not taken, clear the warning and proceed
          warningDiv.textContent = ""; // Clear the warning message
          warningDiv.style.display = "none";
        }
      }
    }
  })
  .catch((error) => {
    console.error("Error occurred while checking SKU:", error);
    warningDiv.textContent = "Error occurred while checking SKU";
    warningDiv.style.display = "block";
  });
  });


  // Form validation function
  function validateForm() {
    var productType = document.getElementById("productType").value;
    var warningDiv = document.getElementById("warning");

    // Check if type is selected
    if (productType === "") {
      warningDiv.textContent = "Please select a type";
      warningDiv.style.display = "block";
      return false;
    }

    // Check SKU, Name, and Price fields
    var sku = document.getElementById("sku").value;
    var name = document.getElementById("name").value;
    var price = document.getElementById("price").value;

    if (sku === "" || name === "" || price === "") {
      warningDiv.textContent = "Please, provide the data of indicated type";
      warningDiv.style.display = "block";
      return false;
    }

    // Check if price is a valid number
    if (isNaN(parseFloat(price))) {
      warningDiv.textContent = "Please, provide a valid price";
      warningDiv.style.display = "block";
      return false;
    }

    // Check required fields based on type
    if (productType === "3") {
      var weight = document.getElementById("weight").value;
      if (weight === "") {
        warningDiv.textContent = "Please, provide the weight";
        warningDiv.style.display = "block";
        return false;
      }
      if (isNaN(parseFloat(weight))) {
        warningDiv.textContent = "Please, provide a valid weight";
        warningDiv.style.display = "block";
        return false;
      }
    } else if (productType === "1") {
      var size = document.getElementById("size").value;
      if (size === "") {
        warningDiv.textContent = "Please, provide the size";
        warningDiv.style.display = "block";
        return false;
      }
      if (isNaN(parseFloat(size))) {
        warningDiv.textContent = "Please, provide a valid size";
        warningDiv.style.display = "block";
        return false;
      }
    } else if (productType === "2") {
      var height = document.getElementById("height").value;
      var width = document.getElementById("width").value;
      var length = document.getElementById("length").value;

      if (height === "" || width === "" || length === "") {
        warningDiv.textContent = "Please, provide the dimensions";
        warningDiv.style.display = "block";
        return false;
      }

      if (
        isNaN(parseFloat(height)) ||
        isNaN(parseFloat(width)) ||
        isNaN(parseFloat(length))
      ) {
        warningDiv.textContent = "Please, provide valid dimensions";
        warningDiv.style.display = "block";
        return false;
      }
    }

    warningDiv.style.display = "none"; // Hide the warning div if all validations pass
    return true; // Submit the form if all validations pass
  }

  // Form submission event
  document.getElementById("product_form").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent the default form submission

    // Call the validateForm function to check if the form is valid
    if (!validateForm()) {
      return; // If the form is not valid, do not proceed with the submission
    }

    var productType = encodeURIComponent(document.getElementById("productType").value);
    var sku = encodeURIComponent(document.getElementById("sku").value);

        fetch("http://localhost/scandiweb-api/actions/read.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ sku: sku }),
        })


      .then((response) => response.json())
      .then((data) => {
        var existingSkus = data.map((product) => product.sku);
        if (existingSkus.includes(sku)) {
          // SKU is already taken, show a warning
          var warningDiv = document.getElementById("warning");
          warningDiv.textContent = "SKU is already used for a different product";
          warningDiv.style.display = "block";
        } else {
          // SKU is not taken, proceed with form submission
          var formData = {
            typeId: productType, // Send 'productType' as 'typeId' in the JSON
            sku: sku,
            name: encodeURIComponent(document.getElementById("name").value),
            price: encodeURIComponent(document.getElementById("price").value),
            product_type_id: productType, // Send 'productType' as 'product_type_id' in the JSON
            weight: encodeURIComponent(document.getElementById("weight").value) || null,
            size: encodeURIComponent(document.getElementById("size").value) || null,
            height: encodeURIComponent(document.getElementById("height").value) || null,
            width: encodeURIComponent(document.getElementById("width").value) || null,
            length: encodeURIComponent(document.getElementById("length").value) || null,
          };

          fetch("http://localhost/scandiweb-api/actions/create.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify(formData),
          })
            .then((response) => {
              if (!response.ok) {
                throw new Error("HTTP error, status = " + response.status);
              }
              return response.json();
            })
            .then((data) => {
              // Check if the request was successful (status 201) and perform the redirect
              if (data.status === 201) {
                window.location.replace("../"); // Replace '../' with the desired URL
              } else {
                alert(data.message); // Display any other error message received from the server
              }
            })
            .catch((error) => {
              console.error("Error:", error);
            });
        }
      })
      .catch((error) => {
        var warningDiv = document.getElementById("warning");
        warningDiv.textContent = "Error occurred while checking SKU";
        warningDiv.style.display = "block";
      });
  });
});
