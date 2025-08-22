
document.title = "Customer Feedback - Syntax On Air";  // Change the page title


const heading = document.querySelector('.feedback-section h2');
heading.style.color = 'darkblue';  //  change heading style
heading.style.textAlign = 'center';


const feedbackSection = document.querySelector('.feedback-section');
feedbackSection.style.backgroundColor = '#f0f8ff';  // Change background color 
feedbackSection.style.padding = '20px';
feedbackSection.style.borderRadius = '10px';


const tableBody = document.querySelector('.feedback-table tbody'); // Add a new row to the feedback table
const newRow = document.createElement('tr');



/*
document
document.body
document.createElement(tagName) 
document.getElementById(id)
element.innerHTML
element.appendChild(node)
element.removeChild(node)
element.textContent
element.classList
element.style */