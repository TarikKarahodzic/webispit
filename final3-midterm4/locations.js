getLocations = () => {
  return fetch("IP2LOCATION.json")
    .then((response) => response.json())
    .then((data) => {
      let output = "";
      data.forEach((location) => {
        output += `
        <div class="card" style="background-color:#F4C2C2; width: 18rem;  margin-right: 10px; margin-top: 10px;">
          <div class="card-body" style=" margin-left:6px;">
            <h5 class="card-title" style="font-weight:bold;">Location</h5>
            <p class="card-text">Country: ${location.Country}</p>
            <p class="card-text">Region: ${location.Region}</p>
            <p class="card-text">City: ${location.City}</p>
            <p class="card-text">Code: ${location.code}</p>
          </div>
        </div>`;
      });
      document.getElementById("locationDiv").innerHTML = output;
    })
    .catch((error) => {
      console.error('Error fetching locations:', error);
    });
}

getDropdown = () => {
  return fetch("IP2LOCATION.json")
    .then((response) => response.json())
    .then((data) => {
      let uniqueCountries = [];
      data.forEach((location) => {
        if (!uniqueCountries.includes(location.Country)) {
          uniqueCountries.push(location.Country);
        }
      });

      let output = "";
      // DROPDOWN
      uniqueCountries.forEach((Country) => {
        output += `
          <li><a class="dropdown-item" data-country="${Country}">${Country}</a></li>
        `;
      });
      document.getElementById("elements").innerHTML = output;

      //DATA TABLE
      const dropdownItems = document.querySelectorAll('.dropdown-item');
      dropdownItems.forEach(item => {
        item.addEventListener('click', () => {
          const selectedCountry = item.getAttribute('data-country');
          let cityCounter = {}; // Object to count occurrences of each city
          let regionCounter = {};
          data.forEach((location) => {
            if (location.Country == selectedCountry) {
              if (!cityCounter[location.City]) {
                cityCounter[location.City] = 1; //Ako grad nije u city counteru doda ga kao key, sa odredjenom vrijednoscu tj bojem
              } else {
                cityCounter[location.City]++; //Ako grad postoji, onda samo poveca broj.
              }

              if (!regionCounter[location.Region]) {
                regionCounter[location.Region] = 1;
              } else {
                regionCounter[location.Region]++;
              }
            }
          });

          let cityHTML = "";
          Object.keys(cityCounter).forEach(city => { //Because CityCounter is an object and not an array we cannot map it, instead we are using the key object names to get the cities
            cityHTML += `
              <tr>
                <td>${city}</td>
                <td>${cityCounter[city]}</td>
              </tr>
            `;
          });

          $(".table > tbody").html(cityHTML);
          $(".modal").modal("show");
          new DataTable("#locations"); // Initialize DataTable


          //PIE CHART
          let pieData = [];
          Object.keys(regionCounter).forEach(region => {
            pieData.push({
              name: region,
              y: regionCounter[region]
            });
          });

          Highcharts.chart('container', {
            chart: {
              type: 'pie' // Correct chart type
            },
            title: {
              text: 'Region Distribution'
            },
            tooltip: {
              pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
              pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                  enabled: true,
                  format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
              }
            },
            series: [{
              name: 'Regions',
              colorByPoint: true,
              data: pieData
            }]
          });

        });
      });
    });
}

// Call the function to fetch and display locations
getLocations();
getDropdown();