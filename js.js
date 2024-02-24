const container = document.querySelector('.container');
const search = document.querySelector('.search-box button');
const weatherBox = document.querySelector('.weather-box');
const weatherDetails = document.querySelector('.weather-details');
const error404 = document.querySelector('.not-found');

// Get a reference to the <a> tag
const link = document.getElementById('pastDaysLink');

const APIKey = 'b163dad8770ca1d50d3d6dbf9019971e';

let weatherData = {};

// Add an event listener for the "keydown" event on the input element
document.querySelector('.search-box input').addEventListener('keydown', (event) => {
  // Check if the pressed key is "Enter" (key code 13)
  if (event.keyCode === 13) {
    // Call the function to fetch data when Enter is pressed
    fetchData();
  }
});

// Add an event listener for the "click" event on the search button
search.addEventListener('click', fetchData);

// Add an event listener to the <a> tag
link.addEventListener('click', () => {
  // Construct the URL with the city parameter from weatherData
  const url = `display.php?city=${encodeURIComponent(weatherData.city)}`;
  // Navigate to the URL in the same tab
  window.location.href = url;
});

function getData(city, APIKey) {
  fetch(`https://api.openweathermap.org/data/2.5/weather?q=${city}&units=metric&appid=${APIKey}`)
    .then(response => response.json())
    .then(json => {
      if (json.cod === '404') {
        container.style.height = '400px';
        weatherBox.style.display = 'none';
        weatherDetails.style.display = 'none';
        error404.style.display = 'block';
        error404.classList.add('fadeIn');
        return;
      }

      error404.style.display = 'none';
      error404.classList.remove('fadeIn');

      console.log('Data fetched from API');

      weatherData = {
        imageSrc: json.weather[0].main,
        temperature: json.main.temp,
        description: json.weather[0].description,
        humidity: json.main.humidity,
        windSpeed: json.wind.speed,
        pressure: json.main.pressure,
        city: json.name
      };

      displayWeatherData(weatherData);
      localStorage.setItem(city, JSON.stringify(weatherData)); // Store data in localStorage
    });
}

function getWeatherImage(weatherMain) {
  switch (weatherMain) {
    case 'Clear':
      return 'img/clear.png';

    case 'Rain':
      return 'img/rain.png';

    case 'Snow':
      return 'img/snow.png';

    case 'Clouds':
      return 'img/clouds.png';

    case 'Mist':
      return 'img/mist.png';

    case 'Wind':
      return 'img/wind.png';

    case 'humidity':
      return 'img/humidity.png';

    default:
      return 'img/clouds.png';
  }
}

function displayWeatherData(weatherData) {
  const weather = weatherData;
  const image = document.querySelector('.weather-box img');
  const temperature = document.querySelector('.weather-box .temperature');
  const description = document.querySelector('.weather-box .description');
  const humidity = document.querySelector('.weather-details .humidity span');
  const wind = document.querySelector('.weather-details .wind span');
  const pressure = document.querySelector('.weather-details .pressure span');

  image.src = getWeatherImage(weatherData.imageSrc);
  temperature.innerHTML = `${weather.temperature}<span>°C</span>`;
  description.innerHTML = weather.description;
  humidity.innerHTML = `${weather.humidity}%`;
  wind.innerHTML = `${weather.windSpeed}Km/h`;
  pressure.innerHTML = `${weather.pressure}hPa`;

  weatherBox.style.display = '';
  weatherDetails.style.display = '';
  weatherBox.classList.add('fadeIn');
  weatherDetails.classList.add('fadeIn');
  container.style.height = '590px';
}

// Define the function to fetch data
function fetchData() {
  let city = document.querySelector('.search-box input').value;

  if (city === '') return;

  // Check if data exists in localStorage
  const storedData = localStorage.getItem(city);
  if (storedData) {
    console.log('Data fetched from local storage');
    weatherData = JSON.parse(storedData);
    displayWeatherData(weatherData);
  } else {
    getData(city, APIKey);
  }
}

// Initial data fetch example (you can remove this or modify it as needed)
getData('Lahān', APIKey);
