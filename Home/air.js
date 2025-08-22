// Detecting browser information using the navigator object
const appName = navigator.appName;             // Returns the browser's name
const appVersion = navigator.appVersion;       // Returns the browser's version
const userAgent = navigator.userAgent;         // Returns the browser's user agent string
const platform = navigator.platform;           // Returns the platform for which the browser is compiled
const language = navigator.language;           // Returns the preferred language of the user

// Creating a message to display based on browser properties
let message = `
    <p><strong>Browser Name:</strong> ${appName}</p>
    <p><strong>Browser Version:</strong> ${appVersion}</p>
    <p><strong>User Agent:</strong> ${userAgent}</p>
    <p><strong>Platform:</strong> ${platform}</p>
    <p><strong>Language:</strong> ${language}</p>
`;

// Applying changes based on detected information
if (language === 'fr') {
    message += "<p>You are using the site in French.</p>";
    document.body.style.backgroundColor = '#ffddcc';
} else if (language === 'en') {
    message += "<p>You are using the site in English.</p>";
    document.body.style.backgroundColor = '#cceeff';
} else {
    message += "<p>We don't recognize your language.</p>";
    document.body.style.backgroundColor = '#ddd';
}

// Changing the website content and appearance based on platform
if (platform.includes('Win')) {
    message += "<p>It looks like you're on a Windows system.</p>";
} else if (platform.includes('Mac')) {
    message += "<p>It looks like you're on a Mac system.</p>";
}

// Displaying the browser information on the webpage
document.getElementById('browserInfo').innerHTML = message;
