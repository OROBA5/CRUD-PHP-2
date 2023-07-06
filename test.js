const apiUrl = 'http://localhost/scandiweb-api/actions/create.php'; // Replace with the actual API endpoint URL

const data = {
  typeId: 1,
  sku: '25',
  name: 'product',
  price: 10.99,
  weight: 20,
  size: 50,
  height: 50,
  width: 50,
  length: 40
};

const options = {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(data)
};

fetch(apiUrl, options)
  .then(response => {
    console.log(response);
    return response.json();
  })
  .then(data => {
    console.log('Response:', data);
    // Handle the response data as needed
  })
  .catch(error => {
    console.error('Error:', error);
    // Handle any errors that occurred during the request
  });
