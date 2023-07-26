document.addEventListener("DOMContentLoaded", function () {

  // Function to display the product list
  function displayProductList(products) {
    var productListDiv = document.querySelector(".product-list");
    productListDiv.innerHTML = "";
  
    products.forEach((product) => {
      var productDiv = document.createElement("div");
      productDiv.classList.add("product-item");
  
      var checkbox = document.createElement("input");
      checkbox.type = "checkbox";
      checkbox.name = "selectedProducts[]";
      checkbox.value = product.product_id; // Use product_id as the checkbox value
      checkbox.className = "delete-checkbox";
      
      var image = document.createElement("img");
      image.className = "product-image";
      if (product.product_type_id === "1") {
        // Book image URL
        image.src = "./image/floppy-disk-solid.svg"; // Adjust the path as per the directory structure
      } else if (product.product_type_id === "2") {
        // DVD image URL
        image.src = "./image/chair-solid.svg"; // Adjust the path as per the directory structure
      } else if (product.product_type_id === "3") {
        // Furniture image URL
        image.src = "./image/book-solid.svg"; // Adjust the path as per the directory structure
      }
  
      var skuSpan = document.createElement("span");
      skuSpan.textContent = "SKU: " + product.sku;
  
      var nameSpan = document.createElement("span");
      nameSpan.textContent = "Name: " + product.name;
  
      var priceSpan = document.createElement("span");
      priceSpan.textContent = "Price: " + product.price + " $ ";
  
      var detailsSpan = document.createElement("span");
      if (product.product_type_id === "1") {
        detailsSpan.textContent = "Size: " + product.size + " MB ";
      } else if (product.product_type_id === "2") {
        detailsSpan.textContent = `Dimensions: ${product.height} x ${product.width} x ${product.length} CM `;
      } else if (product.product_type_id === "3") {
        detailsSpan.textContent = "Weight: " + product.weight + " KG ";
      }
  
      productDiv.appendChild(checkbox);
      productDiv.appendChild(image);
      productDiv.appendChild(skuSpan);
      productDiv.appendChild(nameSpan);
      productDiv.appendChild(priceSpan);
      productDiv.appendChild(detailsSpan);
  
      productListDiv.appendChild(productDiv);
    });
  }
  
    
      // Function to fetch products from the API endpoint
      function fetchProducts() {
        fetch("http://localhost/scandiweb-api/actions/read.php")
          .then((response) => response.json())
          .then((data) => {
            displayProductList(data);
          })
          .catch((error) => {
            console.error("Error:", error);
          });
      }
    
      // Function to handle the "MASS DELETE" button click
      document.getElementById("deleteForm").addEventListener("submit", function (event) {
        event.preventDefault();
    
        var selectedProducts = Array.from(document.querySelectorAll('input[name="selectedProducts[]"]:checked')).map(function (checkbox) {
          return { id: checkbox.value, product_type: checkbox.dataset.productType };
        });
    
        var formData = JSON.stringify(selectedProducts);
    
        console.log("JSON data:", formData); // Display the sent data in the console
    
        var xhr = new XMLHttpRequest();
        xhr.open("POST", this.action, true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function () {
          if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
              console.log(xhr.responseText); // Successful response
              // After successful deletion, fetch and refresh the product list
              fetchProducts();
            } else {
              console.error(xhr.responseText); // Error response
            }
          }
        };
        xhr.send(formData);
      });
    
      // Fetch and display the product list when the page loads
      fetchProducts();
    });
    