// Set up all the necessary variables for use in the code
var operators_chosen;
var difficulty;
var pass_percentage;
var min_no_questions;
var correct = [];
var answers = [];
var incorrectAns = [];
const upload = document.getElementById("upload");
upload.style.display = 'none';

if(document.getElementById("preview") == 'false'){
  const retry = document.getElementById("again");
  retry.style.display = 'none';
}

const equation = document.getElementById("equation");

const results_box = document.getElementsByClassName("results-box")[0];
results_box.style.display = 'none';

const question_box = document.getElementsByClassName("boxy")[0];
const show_results = document.getElementById("reveal_results");
show_results.style.display = 'none';
const present_answers = document.getElementById("answers_presented");
present_answers.style.display = 'none';

const score = document.getElementById("score");
score.style.display = 'none';

const score_hidden = document.getElementById("score_hidden");
const passed_hidden = document.getElementById("passed_hidden");
const correct_ones = document.getElementById("correct_ones");
const incorrect_ones = document.getElementById("incorrect_ones");
const difficulty_sent_off = document.getElementById("difficulty_sent_off");
const opps = document.getElementById("opps");

const score_value = document.getElementsByClassName("score_value")[0];

const points_number = document.getElementById("points-test");
const hints_number = document.getElementById("hints-number");

const submit = document.getElementById("submit");
const error = document.getElementById("error");
error.style.display = 'none';

const points_hidden = document.getElementById("points_hidden");
const duration_hidden = document.getElementById("duration_hidden");

var equation_answer;
var points;

// This can be called to quickly calculate an answer for two integers with an operator in the middle
const calc_operators = {
  '+': (a, b) => a + b,
  '-': (a, b) => a - b,
  '*': (a, b) => a * b,
  '/': (a, b) => a / b,
};

// This function loads the next question
function load_question() {
  // there are three different difficulties: easy medium and hard and they all have different 
  // amounts of answers and operators in their equations
  if (difficulty == "easy") {
    // Gets a random operator from the operator list
    let operator1 = operators_chosen[Math.floor(Math.random() * operators_chosen.length)];

    let answer1;
    let answer2;
    
    // depending on the operator generate a different random value for each
    if(operator1 == '*'){
      answer1 = get_random(2, 13);
      answer2 = get_random(2, 13);
    } else if (operator1 == '/') {
      answer1 = get_random(2, 100);
      answer2 = get_random(2, 15);
    } else if (operator1 == '+') {
      answer1 = get_random(1, 50);
      answer2 = get_random(1, 50);
    } else if (operator1 == '-') {
      answer1 = get_random(1, 50);
      answer2 = get_random(1, 50);
    }
    let isPrime = true;

    // if the two answers doing divide to make an integer then do this
    if ((operator1 == "/") && ((answer1 % answer2) !== 0)) {
      if(answer1 == 1){
        answer1 = get_random(2, 100);
      }
      if((answer1 % answer2) !== 0){
        // check if the number is a prime number
        for (let i = 2; i < answer1; i++) {
            if (answer1 % i == 0) {
                isPrime = false;
                break;
            }
        }
        // if it is a prime number then increase it by one to make it even
        if (isPrime) {
            answer1++;
        }
        // Now we take a random path here: we either divide up or divide down (e.g. start at 12)
        // and keep going to 2 until you find a denominator or go up to 13 from 2
        random_denom = get_random(1, 2);
        if(random_denom == 1){
          for (let index = 12; index > 0; index--) {
            if((answer1 % index) == 0){
              answer2 = index;
              break;
            }               
          }
        } else {
          for (let index = 2; index < 13; index++) {
            if((answer1 % index) == 0){
              answer2 = index;
              break;
            }               
          }
        }
    }
    }

    // generate the solution
    let solution = eval(`${answer1} ${operator1} ${answer2}`);
    
    // generate a equation case using a random number
    let equation_case = get_random(1, 4);
    
    // depending on the case create an equation to fit.
    if (equation_case == 1) {
      // the answer is set here
      equation_answer = [answer1];
      // The equation is created here
      equation.innerHTML = `<input type="number" id="fill_answer" placeholder="?" \> ${operator1} ${answer2} = ${solution}`;
    } else if (equation_case == 2) {
      equation_answer = [answer2];
      equation.innerHTML = `${answer1} ${operator1} <input type="number" id="fill_answer" placeholder="?" \> = ${solution}`;
    } else if (equation_case == 3) {
      equation_answer = [solution];
      equation.innerHTML = `${answer1} ${operator1} ${answer2} = <input type="number" id="fill_answer" placeholder="?" \>`;
    }
    // This is so that the browser automatically clicks into the input field
    document.querySelectorAll('input[type="number"]')[0].focus();

    // This is so that the user can press the 'enter' key to click the submit button instead of having to
    // use a mouse making it a little bit quicker to enter the answer
    document.querySelectorAll('input[type="number"]').forEach(function(input){
      input.addEventListener('keyup', function(event) {
          if (event.keyCode === 13) {
              document.getElementById('submit').click();
          }
      });
  });
  
  // Here is the case if medium difficulty was chosen
  } else if (difficulty == "medium") {
    
    // again we create the answers using a random generator with an additional answer

    // generate the operators
    let operator1 =
      operators_chosen[Math.floor(Math.random() * operators_chosen.length)];
    let operator2 =
      operators_chosen[Math.floor(Math.random() * operators_chosen.length)];

      let answer1;
      let answer2;
      let answer3;
      // Generate different values depending on operator
      if(operator1 == '*'){
        answer1 = get_random(2, 12);
        answer2 = get_random(2, 12);
      } else if (operator1 == '/') {
        answer1 = get_random(2, 100);
        answer2 = get_random(2, 12);
      } else if (operator1 == '+') {
        answer1 = get_random(1, 50);
        answer2 = get_random(1, 50);
      } else if (operator1 == '-') {
        answer1 = get_random(1, 50);
        answer2 = get_random(1, 50);
      }

      // the second operator affects the first operators randomly generated values

      if(operator2 == '*'){
        answer2 = get_random(2, 12);
        answer3 = get_random(2, 12);
      } else if (operator2 == '/') {
        answer2 = get_random(2, 100);
        answer3 = get_random(2, 12);
      } else if (operator2 == '+') {
        answer2 = get_random(1, 50);
        answer3 = get_random(1, 50);
      } else if (operator2 == '-') {
        answer2 = get_random(1, 50);
        answer3 = get_random(1, 50);
      }
  
      let isPrime = true;

      // if the two answers doing divide to make an integer then do this
      if (operator1 == "/") {
        if(answer1 == 1){
          answer1 = get_random(2, 100);
        }
        if((answer1 % answer2) !== 0){
          // check if the number is a prime number
          for (let i = 2; i < answer1; i++) {
              if (answer1 % i == 0) {
                  isPrime = false;
                  break;
              }
          }
          if (isPrime) {
              answer1++;
          }
          random_denom = get_random(1, 2);
          if(random_denom == 1){
            for (let index = 12; index > 1; index--) {
              if((answer1 % index) == 0){
                answer2 = index;
                break;
              }               
            }
          } else {
            for (let index = 2; index < 13; index++) {
              if((answer1 % index) == 0){
                answer2 = index;
                break;
              }               
            }
          }
      }

      if((operator2 == "/") && (((answer1 / answer2) % answer3) !== 0)){
        if((answer1/answer2) == 1) {
          answer3 = 1;
        } else if(((answer1 / answer2) % answer3) !== 0){

          for (let i = 2; i < (answer1 / answer2); i++) {
              if ((answer1 / answer2) % i == 0) {
                  isPrime = false;
                  break;
              }
          }
          if (isPrime) {
            answer3 = 1;
          } else {
            random_denom = get_random(1, 2);
            let found = false;
            if(random_denom == 1){
              for (let index = 12; index > 1; index--) {
                if(((answer1 / answer2) % index) == 0){
                  found = true;
                  answer3 = index;
                  break;
                }               
              }
              if(found == false){
                answer3 = 1;
              }
            } else {
              for (let index = 2; index < 13; index++) {
                if(((answer1 / answer2) % index) == 0){
                  found = true;
                  answer3 = index;
                  break;
                }               
              }
              if(found == false){
                answer3 = 1;
              }

            }
          }
      }
    
    }
      }

  // If the case is that operator1 isnt a divisor then check the remainder of answer2 and 3
  if ((operator2 == "/") && ((answer2 % answer3) !== 0) && operator1 != '/') {
    if(answer2 == 1){
      answer2 = get_random(2, 100);
    }

    if((answer2 % answer3) !== 0){
      for (let i = 2; i < answer2; i++) {
          if (answer2 % i == 0) {
              isPrime = false;
              break;
          }
      }
      if (isPrime) {
          answer2++;
      }
      random_denom = get_random(1, 2);
      let found = false;
      if(random_denom == 1){
        for (let index = 12; index > 1; index--) {
          if((answer2 % index) == 0){
            found = true;
            answer3 = index;
            break;
          }               
        }
        if(found == false){
          answer3 = 1;
        }
      } else {
        for (let index = 2; index < 13; index++) {
          if((answer2 % index) == 0){
            found = true;
            answer3 = index;
            break;
          }               
        }
        if(found == false){
          answer3 = 1;
        }
      }

  }

  
  }

    //Solve equation
    let solution = eval(`${answer1} ${operator1} ${answer2} ${operator2} ${answer3}`);

    let equation_case = get_random(1, 5);

     if (equation_case == 1) {
      equation_answer = [solution];
      if(operator1 == '/' && operator2 != '/'){
      equation.innerHTML = `(${answer1} ${operator1} ${answer2}) ${operator2} ${answer3} = <input type="number" id="fill_answer" placeholder="?"\>`;
      } else if (operator1 != '/' && operator2 == '/'){
        equation.innerHTML = `${answer1} ${operator1} (${answer2} ${operator2} ${answer3}) = <input type="number" id="fill_answer" placeholder="?"\>`;
      } else if (operator1 == '*' && operator2 != '*'){
        equation.innerHTML = `(${answer1} ${operator1} ${answer2}) ${operator2} ${answer3} = <input type="number" id="fill_answer" placeholder="?"\>`;
      } else if (operator1 != '*' && operator2 == '*'){
        equation.innerHTML = `${answer1} ${operator1} (${answer2} ${operator2} ${answer3}) = <input type="number" id="fill_answer" placeholder="?"\>`;
      } else {
        equation.innerHTML = `${answer1} ${operator1} ${answer2} ${operator2} ${answer3} = <input type="number" id="fill_answer" placeholder="?"\>`;
      }
    } else if (equation_case == 2) {
      equation_answer = [answer1];
      if(operator1 == '/' && operator2 != '/'){
        equation.innerHTML = `(<input type="number" id="fill_answer" placeholder="?"\> ${operator1} ${answer2}) ${operator2} ${answer3} = ${solution}`;
        } else if (operator1 != '/' && operator2 == '/'){
          equation.innerHTML = `<input type="number" id="fill_answer" placeholder="?"\> ${operator1} (${answer2} ${operator2} ${answer3}) = ${solution}`;
        } else if (operator1 == '*' && operator2 != '*'){
          equation.innerHTML = `(<input type="number" id="fill_answer" placeholder="?"\> ${operator1} ${answer2}) ${operator2} ${answer3} = ${solution}`;
        } else if (operator1 != '*' && operator2 == '*'){
          equation.innerHTML = `<input type="number" id="fill_answer" placeholder="?"\> ${operator1} (${answer2} ${operator2} ${answer3}) = ${solution}`;
        } else {
          equation.innerHTML = `<input type="number" id="fill_answer" placeholder="?"\> ${operator1} ${answer2} ${operator2} ${answer3} = ${solution}`;
        }
    } else if (equation_case == 3) {
      equation_answer = [answer2];
      if(operator1 == '/' && operator2 != '/'){
        equation.innerHTML = `(${answer1} ${operator1} <input type="number" id="fill_answer" placeholder="?"\>) ${operator2} ${answer3} = ${solution}`;
      } else if (operator1 != '/' && operator2 == '/'){
        equation.innerHTML = `${answer1} ${operator1} (<input type="number" id="fill_answer" placeholder="?"\> ${operator2} ${answer3}) = ${solution}`;
        } else if (operator1 == '*' && operator2 != '*'){
          equation.innerHTML = `(${answer1} ${operator1} <input type="number" id="fill_answer" placeholder="?"\>) ${operator2} ${answer3} = ${solution}`;
        } else if (operator1 != '*' && operator2 == '*'){
          equation.innerHTML = `${answer1} ${operator1} (<input type="number" id="fill_answer" placeholder="?"\> ${operator2} ${answer3}) = ${solution}`;
        } else {
          equation.innerHTML = `${answer1} ${operator1} <input type="number" id="fill_answer" placeholder="?"\> ${operator2} ${answer3} = ${solution}`;
        }
    } else if (equation_case == 4) {
      equation_answer = [answer3];
      if(operator1 == '/' && operator2 != '/'){
        equation.innerHTML = `(${answer1} ${operator1} ${answer2}) ${operator2} <input type="number" id="fill_answer" placeholder="?"\> = ${solution}`;
      } else if (operator1 != '/' && operator2 == '/'){
        equation.innerHTML = `${answer1} ${operator1} (${answer2} ${operator2} <input type="number" id="fill_answer" placeholder="?"\>) = ${solution}`;
      } else if (operator1 == '*' && operator2 != '*'){
          equation.innerHTML = `(${answer1} ${operator1} ${answer2}) ${operator2} <input type="number" id="fill_answer" placeholder="?"\> = ${solution}`;
        } else if (operator1 != '*' && operator2 == '*'){
          equation.innerHTML = `${answer1} ${operator1} (${answer2} ${operator2} <input type="number" id="fill_answer" placeholder="?"\>) = ${solution}`;
        } else {
          equation.innerHTML = `${answer1} ${operator1} ${answer2} ${operator2} <input type="number" id="fill_answer" placeholder="?"\> = ${solution}`;
        }
      equation.innerHTML = `${answer1} ${operator1} ${answer2} ${operator2} <input type="number" id="fill_answer" placeholder="?"\> = ${solution}`;
    } 
        document.querySelectorAll('input[type="number"]')[0].focus();

        document.querySelectorAll('input[type="number"]').forEach(function(input){
          input.addEventListener('keyup', function(event) {
              if (event.keyCode === 13) {
                  document.getElementById('submit').click();
              }
          });
      });
      
    
  } else if (difficulty == "hard") {

    //For getting random operator
    let operator1 =
      operators_chosen[Math.floor(Math.random() * operators_chosen.length)];
    let operator2 =
      operators_chosen[Math.floor(Math.random() * operators_chosen.length)];
    let operator3 =
      operators_chosen[Math.floor(Math.random() * operators_chosen.length)];


      let answer1;
      let answer2;
      let answer3;
      let answer4;
      if(operator1 == '*'){
        answer1 = get_random(2, 12);
        answer2 = get_random(2, 12);
      } else if (operator1 == '/') {
        answer1 = get_random(2, 100);
        answer2 = get_random(2, 12);
      } else if (operator1 == '+') {
        answer1 = get_random(1, 50);
        answer2 = get_random(1, 50);
      } else if (operator1 == '-') {
        answer1 = get_random(1, 50);
        answer2 = get_random(1, 50);
      }

      if(operator2 == '*'){
        answer2 = get_random(2, 12);
        answer3 = get_random(2, 12);
      } else if (operator2 == '/') {
        answer2 = get_random(2, 100);
        answer3 = get_random(2, 12);
      } else if (operator2 == '+') {
        answer2 = get_random(1, 50);
        answer3 = get_random(1, 50);
      } else if (operator2 == '-') {
        answer2 = get_random(1, 50);
        answer3 = get_random(1, 50);
      }

      if(operator3 == '*'){
        answer3 = get_random(2, 12);
        answer4 = get_random(2, 12);
      } else if (operator3 == '/') {
        answer3 = get_random(2, 100);
        answer4 = get_random(2, 12);
      } else if (operator3 == '+') {
        answer3 = get_random(1, 50);
        answer4 = get_random(1, 50);
      } else if (operator3 == '-') {
        answer3 = get_random(1, 50);
        answer4 = get_random(1, 50);
      }



    let isPrime = true;





    if (operator1 == "/") {
      if(answer1 == 1){
        answer1 = get_random(2, 100);
      }
      if((answer1 % answer2) !== 0){
        // check if the number is a prime number
        for (let i = 2; i < answer1; i++) {
            if (answer1 % i == 0) {
                isPrime = false;
                break;
            }
        }
        // if it is a prime number then increase it by one to make it even and keep generating 
        // a second answer till a denominator is found
        if (isPrime) {
            answer1++;
        }
        random_denom = get_random(1, 2);
        if(random_denom == 1){
          for (let index = 12; index > 1; index--) {
            if((answer1 % index) == 0){
              answer2 = index;
              break;
            }               
          }
        } else {
          for (let index = 2; index < 13; index++) {
            if((answer1 % index) == 0){
              answer2 = index;
              break;
            }               
          }
        }
    }

    if((operator2 == "/") && (((answer1 / answer2) % answer3) !== 0)){
      if((answer1/answer2) == 1) {
        answer3 = 1;
      } else if(((answer1 / answer2) % answer3) !== 0){

        for (let i = 2; i < (answer1 / answer2); i++) {
            if ((answer1 / answer2) % i == 0) {
                isPrime = false;
                break;
            }
        }
        if (isPrime) {
          answer3 = 1;
        } else {
          random_denom = get_random(1, 2);
          let found = false;
          if(random_denom == 1){
            for (let index = 12; index > 1; index--) {
              if(((answer1 / answer2) % index) == 0){
                found = true;
                answer3 = index;
                break;
              }               
            }
            if(found == false){
              answer3 = 1;
            }
          } else {
            for (let index = 2; index < 13; index++) {
              if(((answer1 / answer2) % index) == 0){
                found = true;
                answer3 = index;
                break;
              }               
            }
            if(found == false){
              answer3 = 1;
            }

          }
        }
    }
  
  }


  if((operator3 == "/") && (((answer1/answer2/answer3) % answer4) !== 0) && (operator2 == "/")){

    if((answer1/answer2/answer3) == 1) {
      answer4 = 1;
    } else if(((answer1/answer2/answer3) % answer4) !== 0){
      for (let i = 2; i < (answer1/answer2/answer3); i++) {
          if ((answer1/answer2/answer3) % i == 0) {
              isPrime = false;
              break;
          }
      }
      if (isPrime) {
        answer4 = 1;
      } else {
        random_denom = get_random(1, 2);
        let found = false;
        if(random_denom == 1){
          for (let index = 12; index > 1; index--) {
            if(((answer1 / answer2 / answer3) % index) == 0){
              found = true;
              answer4 = index;
              break;
            }               
          }
          if(found == false){
            answer4 = 1;
          }
        } else {
          for (let index = 2; index < 13; index++) {
            if(((answer1 / answer2 / answer3) % index) == 0){
              found = true;
              answer4 = index;
              break;
            }               
          }
          if(found == false){
            answer4 = 1;
          }

        }

      }
  }

}
  }



    if ((operator2 == "/") && ((answer2 % answer3) !== 0) && operator1 != '/') {
      



      if(answer2 == 1){
        answer2 = get_random(2, 100);
      }
      if((answer2 % answer3) !== 0){
        // check if the number is a prime number
        for (let i = 2; i < answer2; i++) {
            if (answer2 % i == 0) {
                isPrime = false;
                break;
            }
        }
        // if it is a prime number then increase it by one to make it even and keep generating 
        // a second answer till a denominator is found
        if (isPrime) {
            answer2++;
        }
        random_denom = get_random(1, 2);
        if(random_denom == 1){
          for (let index = 12; index > 1; index--) {
            if((answer2 % index) == 0){
              answer3 = index;
              break;
            }               
          }
        } else {
          for (let index = 2; index < 13; index++) {
            if((answer2 % index) == 0){
              answer3 = index;
              break;
            }               
          }
        }
    }



    if((operator3 == "/") && (((answer2/answer3) % answer4) !== 0)){
      if((answer2/answer3) == 1) {
        answer4 = 1;
      } else if(((answer2 / answer3) % answer4) !== 0){
        for (let i = 2; i < (answer2 / answer3); i++) {
            if ((answer2 / answer3) % i == 0) {
                isPrime = false;
                break;
            }
        }
        if (isPrime) {
          answer4 = 1;
        } else {
          random_denom = get_random(1, 2);
          let found = false;
          if(random_denom == 1){
            for (let index = 12; index > 1; index--) {
              if(((answer2 / answer3) % index) == 0){
                found = true;
                answer4 = index;
                break;
              }               
            }
            if(found == false){
              answer4 = 1;
            }
          } else {
            for (let index = 2; index < 13; index++) {
              if(((answer2 / answer3) % index) == 0){
                found = true;
                answer4 = index;
                break;
              }               
            }
            if(found == false){
              answer4 = 1;
            }

          }
        }
    }
  
  }
}

        if((operator3 == "/") && ((answer3 % answer4) !== 0) && (operator2 != "/")){
          if(answer3 == 1){
            answer3 = get_random(2, 100);
          }
          if((answer3 % answer4) !== 0){
            // check if the number is a prime number
            for (let i = 2; i < answer3; i++) {
                if (answer3 % i == 0) {
                    isPrime = false;
                    break;
                }
            }
            // if it is a prime number then increase it by one to make it even and keep generating 
            // a second answer till a denominator is found
            if (isPrime) {
                answer3++;
            }
            random_denom = get_random(1, 2);
            if(random_denom == 1){
              for (let index = 12; index > 1; index--) {
                if((answer3 % index) == 0){
                  answer4 = index;
                  break;
                }               
              }
            } else {
              for (let index = 2; index < 13; index++) {
                if((answer3 % index) == 0){
                  answer4 = index;
                  break;
                }               
              }
            }
        }      
        }

    let solution = eval(`${answer1} ${operator1} ${answer2} ${operator2} ${answer3} ${operator3} ${answer4}`);

    let equation_case = get_random(1, 6);

    // These are the cases for the hard move there are 5 different places that can be empty
    // I have considered all cases and chose to put brackets around certain numbers to make it 
    // easier for the user to work out the answer
    if (equation_case == 1) {
      equation_answer = [answer4];
      if((operator1 == '*' && operator2 == '*' && operator3 == '*') || (operator1 == '/' && operator2 == '/' && operator3 == '/')){
        equation.innerHTML = `${answer1} ${operator1} ${answer2} ${operator2} ${answer3} ${operator3} <input type="number" id="fill_answer" placeholder="?"\> = ${solution}`;
      } else if (operator1 == '/' && operator2 == '/') {
        equation.innerHTML = `((${answer1} ${operator1} ${answer2}) ${operator2} ${answer3}) ${operator3} <input type="number" id="fill_answer" placeholder="?"\> = ${solution}`;
       } else if (operator2 == '/' && operator3 == '/') {
        equation.innerHTML = `${answer1} ${operator1} ((${answer2} ${operator2} ${answer3}) ${operator3} <input type="number" id="fill_answer" placeholder="?"\>) = ${solution}`;
       } else if (operator1 == '/' && operator3 == '/' || operator1 == '/' && operator3 == '*' || operator1 == '*' && operator3 == '/') {
        equation.innerHTML = `(${answer1} ${operator1} ${answer2}) ${operator2} (${answer3} ${operator3} <input type="number" id="fill_answer" placeholder="?"\>) = ${solution}`;
       } else if (operator1 == '/') {
        equation.innerHTML = `(${answer1} ${operator1} ${answer2}) ${operator2} ${answer3} ${operator3} <input type="number" id="fill_answer" placeholder="?"\> = ${solution}`;
       } else if (operator2 == '/') {
        equation.innerHTML = `${answer1} ${operator1} (${answer2} ${operator2} ${answer3}) ${operator3} <input type="number" id="fill_answer" placeholder="?"\> = ${solution}`;
       } else if (operator3 == '/') {
        equation.innerHTML = `${answer1} ${operator1} ${answer2} ${operator2} (${answer3} ${operator3} <input type="number" id="fill_answer" placeholder="?"\>) = ${solution}`;
      } else if (operator1 == '*' && operator2 == '*') {
        equation.innerHTML = `((${answer1} ${operator1} ${answer2}) ${operator2} ${answer3}) ${operator3} <input type="number" id="fill_answer" placeholder="?"\> = ${solution}`;
       } else if (operator2 == '*' && operator3 == '*') {
        equation.innerHTML = `${answer1} ${operator1} ((${answer2} ${operator2} ${answer3}) ${operator3} <input type="number" id="fill_answer" placeholder="?"\>) = ${solution}`;
       } else if (operator1 == '*') {
        equation.innerHTML = `(${answer1} ${operator1} ${answer2}) ${operator2} ${answer3} ${operator3} <input type="number" id="fill_answer" placeholder="?"\> = ${solution}`;
       } else if (operator2 == '*') {
        equation.innerHTML = `${answer1} ${operator1} (${answer2} ${operator2} ${answer3}) ${operator3} <input type="number" id="fill_answer" placeholder="?"\> = ${solution}`;
       } else if (operator3 == '*') {
        equation.innerHTML = `${answer1} ${operator1} ${answer2} ${operator2} (${answer3} ${operator3} <input type="number" id="fill_answer" placeholder="?"\>) = ${solution}`;
       } else {
        equation.innerHTML = `${answer1} ${operator1} ${answer2} ${operator2} ${answer3} ${operator3} <input type="number" id="fill_answer" placeholder="?"\> = ${solution}`;
       }
    } else if (equation_case == 2) {
      equation_answer = [answer3];
      if((operator1 == '*' && operator2 == '*' && operator3 == '*') || (operator1 == '/' && operator2 == '/' && operator3 == '/')){
        equation.innerHTML = `${answer1} ${operator1} ${answer2} ${operator2} <input type="number" id="fill_answer" placeholder="?"\> ${operator3} ${answer4} = ${solution}`;
      } else if (operator1 == '/' && operator2 == '/') {
        equation.innerHTML = `((${answer1} ${operator1} ${answer2}) ${operator2} <input type="number" id="fill_answer" placeholder="?"\>) ${operator3} ${answer4} = ${solution}`;
       } else if (operator2 == '/' && operator3 == '/') {
        equation.innerHTML = `${answer1} ${operator1} ((${answer2} ${operator2} <input type="number" id="fill_answer" placeholder="?"\>) ${operator3} ${answer4}) = ${solution}`;
       } else if (operator1 == '/' && operator3 == '/' || operator1 == '/' && operator3 == '*' || operator1 == '*' && operator3 == '/') {
        equation.innerHTML = `(${answer1} ${operator1} ${answer2}) ${operator2} (<input type="number" id="fill_answer" placeholder="?"\> ${operator3} ${answer4}) = ${solution}`;
       } else if (operator1 == '/') {
        equation.innerHTML = `(${answer1} ${operator1} ${answer2}) ${operator2} <input type="number" id="fill_answer" placeholder="?"\>) ${operator3} ${answer4} = ${solution}`;
       } else if (operator2 == '/') {
        equation.innerHTML = `${answer1} ${operator1} (${answer2} ${operator2} <input type="number" id="fill_answer" placeholder="?"\>) ${operator3} ${answer4} = ${solution}`;
       } else if (operator3 == '/') {
        equation.innerHTML = `${answer1} ${operator1} ${answer2} ${operator2} (<input type="number" id="fill_answer" placeholder="?"\> ${operator3} ${answer4}) = ${solution}`;
      } else if (operator1 == '*' && operator2 == '*') {
        equation.innerHTML = `((${answer1} ${operator1} ${answer2}) ${operator2} <input type="number" id="fill_answer" placeholder="?"\>) ${operator3} ${answer4} = ${solution}`;
       } else if (operator2 == '*' && operator3 == '*') {
        equation.innerHTML = `${answer1} ${operator1} ((${answer2} ${operator2} <input type="number" id="fill_answer" placeholder="?"\>) ${operator3} ${answer4}) = ${solution}`;
       } else if (operator1 == '*') {
        equation.innerHTML = `(${answer1} ${operator1} ${answer2}) ${operator2} <input type="number" id="fill_answer" placeholder="?"\>) ${operator3} ${answer4} = ${solution}`;
       } else if (operator2 == '*') {
        equation.innerHTML = `${answer1} ${operator1} (${answer2} ${operator2} <input type="number" id="fill_answer" placeholder="?"\>) ${operator3} ${answer4} = ${solution}`;
       } else if (operator3 == '*') {
        equation.innerHTML = `${answer1} ${operator1} ${answer2} ${operator2} (<input type="number" id="fill_answer" placeholder="?"\> ${operator3} ${answer4}) = ${solution}`;
       } else {
        equation.innerHTML = `${answer1} ${operator1} ${answer2} ${operator2} <input type="number" id="fill_answer" placeholder="?"\> ${operator3} ${answer4} = ${solution}`;
       }
    } else if (equation_case == 3) {
      equation_answer = [answer1];
      if((operator1 == '*' && operator2 == '*' && operator3 == '*') || (operator1 == '/' && operator2 == '/' && operator3 == '/')){
        equation.innerHTML = `<input type="number" id="fill_answer" placeholder="?"\> ${operator1} ${answer2} ${operator2} ${answer3} ${operator3} ${answer4} = ${solution}`;
      } else if (operator1 == '/' && operator2 == '/') {
        equation.innerHTML = `((<input type="number" id="fill_answer" placeholder="?"\> ${operator1} ${answer2}) ${operator2} ${answer3}) ${operator3} ${answer4} = ${solution}`;
       } else if (operator2 == '/' && operator3 == '/') {
        equation.innerHTML = `<input type="number" id="fill_answer" placeholder="?"\> ${operator1} ((${answer2} ${operator2} ${answer3}) ${operator3} ${answer4}) = ${solution}`;
       } else if (operator1 == '/' && operator3 == '/' || operator1 == '/' && operator3 == '*' || operator1 == '*' && operator3 == '/') {
        equation.innerHTML = `(<input type="number" id="fill_answer" placeholder="?"\> ${operator1} ${answer2}) ${operator2} (${answer3} ${operator3} ${answer4}) = ${solution}`;
       } else if (operator1 == '/') {
        equation.innerHTML = `(<input type="number" id="fill_answer" placeholder="?"\> ${operator1} ${answer2}) ${operator2} ${answer3} ${operator3} ${answer4} = ${solution}`;
       } else if (operator2 == '/') {
        equation.innerHTML = `<input type="number" id="fill_answer" placeholder="?"\> ${operator1} (${answer2} ${operator2} ${answer3}) ${operator3} ${answer4} = ${solution}`;
       } else if (operator3 == '/') {
        equation.innerHTML = `<input type="number" id="fill_answer" placeholder="?"\> ${operator1} ${answer2} ${operator2} (${answer3} ${operator3} ${answer4}) = ${solution}`;
      } else if (operator1 == '*' && operator2 == '*') {
        equation.innerHTML = `((<input type="number" id="fill_answer" placeholder="?"\> ${operator1} ${answer2}) ${operator2} ${answer3}) ${operator3} ${answer4} = ${solution}`;
       } else if (operator2 == '*' && operator3 == '*') {
        equation.innerHTML = `<input type="number" id="fill_answer" placeholder="?"\> ${operator1} ((${answer2} ${operator2} ${answer3}) ${operator3} ${answer4}) = ${solution}`;
       } else if (operator1 == '*') {
        equation.innerHTML = `(<input type="number" id="fill_answer" placeholder="?"\> ${operator1} ${answer2}) ${operator2} ${answer3} ${operator3} ${answer4} = ${solution}`;
       } else if (operator2 == '*') {
        equation.innerHTML = `<input type="number" id="fill_answer" placeholder="?"\> ${operator1} (${answer2} ${operator2} ${answer3}) ${operator3} ${answer4} = ${solution}`;
       } else if (operator3 == '*') {
        equation.innerHTML = `<input type="number" id="fill_answer" placeholder="?"\> ${operator1} ${answer2} ${operator2} (${answer3} ${operator3} ${answer4}) = ${solution}`;
       } else {
        equation.innerHTML = `<input type="number" id="fill_answer" placeholder="?"\> ${operator1} ${answer2} ${operator2} ${answer3} ${operator3} ${answer4} = ${solution}`;
      }
    } else if (equation_case == 4) {
      equation_answer = [answer2];
      if((operator1 == '*' && operator2 == '*' && operator3 == '*') || (operator1 == '/' && operator2 == '/' && operator3 == '/')){
        equation.innerHTML = `${answer1} ${operator1} <input type="number" id="fill_answer" placeholder="?"\> ${operator2} ${answer3} ${operator3} ${answer4} = ${solution}`;
      } else if (operator1 == '/' && operator2 == '/') {
        equation.innerHTML = `((${answer1} ${operator1} <input type="number" id="fill_answer" placeholder="?"\>) ${operator2} ${answer3}) ${operator3} ${answer4} = ${solution}`;
       } else if (operator2 == '/' && operator3 == '/') {
        equation.innerHTML = `${answer1} ${operator1} ((<input type="number" id="fill_answer" placeholder="?"\> ${operator2} ${answer3}) ${operator3} ${answer4}) = ${solution}`;
       } else if (operator1 == '/' && operator3 == '/' || operator1 == '/' && operator3 == '*' || operator1 == '*' && operator3 == '/') {
        equation.innerHTML = `(${answer1} ${operator1} <input type="number" id="fill_answer" placeholder="?"\>) ${operator2} (${answer3} ${operator3} ${answer4}) = ${solution}`;
       } else if (operator1 == '/') {
        equation.innerHTML = `(${answer1} ${operator1} <input type="number" id="fill_answer" placeholder="?"\>) ${operator2} ${answer3} ${operator3} ${answer4} = ${solution}`;
       } else if (operator2 == '/') {
        equation.innerHTML = `${answer1} ${operator1} (<input type="number" id="fill_answer" placeholder="?"\> ${operator2} ${answer3}) ${operator3} ${answer4} = ${solution}`;
       } else if (operator3 == '/') {
        equation.innerHTML = `${answer1} ${operator1} <input type="number" id="fill_answer" placeholder="?"\> ${operator2} (${answer3} ${operator3} ${answer4}) = ${solution}`;
      } else if (operator1 == '*' && operator2 == '*') {
        equation.innerHTML = `((${answer1} ${operator1} <input type="number" id="fill_answer" placeholder="?"\>) ${operator2} ${answer3}) ${operator3} ${answer4} = ${solution}`;
       } else if (operator2 == '*' && operator3 == '*') {
        equation.innerHTML = `${answer1} ${operator1} ((<input type="number" id="fill_answer" placeholder="?"\> ${operator2} ${answer3}) ${operator3} ${answer4}) = ${solution}`;
       } else if (operator1 == '*') {
        equation.innerHTML = `(${answer1} ${operator1} <input type="number" id="fill_answer" placeholder="?"\>) ${operator2} ${answer3} ${operator3} ${answer4} = ${solution}`;
       } else if (operator2 == '*') {
        equation.innerHTML = `${answer1} ${operator1} (<input type="number" id="fill_answer" placeholder="?"\> ${operator2} ${answer3}) ${operator3} ${answer4} = ${solution}`;
       } else if (operator3 == '*') {
        equation.innerHTML = `${answer1} ${operator1}  <input type="number" id="fill_answer" placeholder="?"\> ${operator2} (${answer3} ${operator3} ${answer4}) = ${solution}`;
       } else {
      equation.innerHTML = `${answer1} ${operator1} <input type="number" id="fill_answer" placeholder="?"\> ${operator2} ${answer3} ${operator3} ${answer4} = ${solution}`;
    }
    } else if (equation_case == 5) {
      equation_answer = [solution];
      if((operator1 == '*' && operator2 == '*' && operator3 == '*') || (operator1 == '/' && operator2 == '/' && operator3 == '/')){
        equation.innerHTML = `${answer1} ${operator1} ${answer2} ${operator2} ${answer3} ${operator3} ${answer4} = <input type="number" id="fill_answer" placeholder="?"\>`;
      } else if (operator1 == '/' && operator2 == '/') {
        equation.innerHTML = `((${answer1} ${operator1} ${answer2}) ${operator2} ${answer3}) ${operator3} ${answer4} = <input type="number" id="fill_answer" placeholder="?"\>`;
       } else if (operator2 == '/' && operator3 == '/') {
        equation.innerHTML = `${answer1} ${operator1} ((${answer2} ${operator2} ${answer3}) ${operator3} ${answer4}) = <input type="number" id="fill_answer" placeholder="?"\>`;
       } else if (operator1 == '/' && operator3 == '/' || operator1 == '/' && operator3 == '*' || operator1 == '*' && operator3 == '/') {
        equation.innerHTML = `(${answer1} ${operator1} ${answer2}) ${operator2} (${answer3} ${operator3} ${answer4}) = <input type="number" id="fill_answer" placeholder="?"\>`;
       } else if (operator1 == '/') {
        equation.innerHTML = `(${answer1} ${operator1} ${answer2}) ${operator2} ${answer3} ${operator3} ${answer4} = <input type="number" id="fill_answer" placeholder="?"\>`;
       } else if (operator2 == '/') {
        equation.innerHTML = `${answer1} ${operator1} (${answer2} ${operator2} ${answer3}) ${operator3} ${answer4} = <input type="number" id="fill_answer" placeholder="?"\>`;
       } else if (operator3 == '/') {
        equation.innerHTML = `${answer1} ${operator1} ${answer2} ${operator2} (${answer3} ${operator3} ${answer4}) = <input type="number" id="fill_answer" placeholder="?"\>`;
      } else if (operator1 == '*' && operator2 == '*') {
        equation.innerHTML = `((${answer1} ${operator1} ${answer2}) ${operator2} ${answer3}) ${operator3} ${answer4} = <input type="number" id="fill_answer" placeholder="?"\>`;
       } else if (operator2 == '*' && operator3 == '*') {
        equation.innerHTML = `${answer1} ${operator1} ((${answer2} ${operator2} ${answer3}) ${operator3} ${answer4}) = <input type="number" id="fill_answer" placeholder="?"\>`;
       } else if (operator1 == '*') {
        equation.innerHTML = `(${answer1} ${operator1} ${answer2}) ${operator2} ${answer3} ${operator3} ${answer4} = <input type="number" id="fill_answer" placeholder="?"\>`;
       } else if (operator2 == '*') {
        equation.innerHTML = `${answer1} ${operator1} (${answer2} ${operator2} ${answer3}) ${operator3} ${answer4} = <input type="number" id="fill_answer" placeholder="?"\>`;
       } else if (operator3 == '*') {
        equation.innerHTML = `${answer1} ${operator1} ${answer2} ${operator2} (${answer3} ${operator3} ${answer4}) = <input type="number" id="fill_answer" placeholder="?"\>`;
       } else {
        equation.innerHTML = `${answer1} ${operator1} ${answer2} ${operator2} ${answer3} ${operator3} ${answer4} = <input type="number" id="fill_answer" placeholder="?"\>`;
      }
    }
        document.querySelectorAll('input[type="number"]')[0].focus();

        document.querySelectorAll('input[type="number"]').forEach(function(input){
          input.addEventListener('keyup', function(event) {
              if (event.keyCode === 13) {
                  document.getElementById('submit').click();
              }
          });
      });
  }
}

var incorrect = false;

// If you click the submit button this function is accessed
submit.onclick = function () {
  // create the variables for use in the function
  let incorrect_answer = "no";
  let empty_input = "no";
  error.style.display = 'none';
  // checks what the inputted answer is
  let numberInputs = [document.getElementById('fill_answer')];
  let inputsArray = [];
  // if it has a value then compare it to actual value else show errors
  if(numberInputs[0].value){
    inputsArray = [numberInputs[0].value.toString()];
    if (numberInputs[0].value != equation_answer[0]) {
      incorrect_answer = "yes";
    }
  } else {
    empty_input = "yes";
  }

  if (empty_input == "yes") {
    error.style.display = 'block';
    error.innerHTML = "Must input a number to proceed";
  } else if (incorrect_answer == "no") {
    // if it is a correct answer then upadate the points and the line together so that it can be shown up at the end
    if(incorrect != true){
      let points = parseInt(points_number.innerHTML) + 100;
      points_number.innerHTML = points.toString();
    }
    equation_answer = "";
    error.innerHTML = "";
    error.style.display = 'none';
    let arr = [];
    let split_string = equation.innerHTML.split(/<[^>]+>/);
    for (let index = 0; index < split_string.length; index++) {
      const element = split_string[index].split(" ");
      if((element[0] == '')  && (index != 0) && (inputsArray.length > 1)){
        element.shift();
      }
      arr.push(element);
    }
    let combinedArray = [];
    if(arr[0].length == 1 && arr[0][0] == '' && arr.length == 2){
      combinedArray = arr[1];
    } else {
      combinedArray = [].concat(...arr);
    }
    // Add an I if it was a correct element else add a C so that at the end we can show up the integer
    // surrounded by a green or red square
    for (let i = 0; i < inputsArray.length; i++) {
      const element = inputsArray[i];
      for (let j = 0; j < combinedArray.length; j++) {
        if(combinedArray[j] == ''){
          let found = false;
            if(incorrect_index.includes(i)){
              combinedArray[j] = element + "I" // I for incorrect
              found = true;
              incorrect = true;
              break;  
            } else {
              combinedArray[j] = element + "C" // C for correct
              break;

            }
          }
        }
      }
      incorrect_index = [];

    answers.push(combinedArray); // this array will hold all correct and incorrect answers and will show what I got right and wrong at the very end

    if(incorrect == true){
      incorrectAns.push(combinedArray); // This just holds incorrect and is used to total up at the end
      incorrect = false;
    } else {
      correct.push(combinedArray) // This just holds correct and is used to total up at the end
    }

    // make the background green for a split second

    setTimeout(function() {
      document.body.style.background = "green";
    }, 1);

    setTimeout(function() {
      document.body.style.background = "lightblue";
    }, 400);
    // load up another question
    load_question();
    
    // reduce the time and increase the round number if it is the case that 5 questions have been answered
    if(answers.length % 5 == 0){
      let hints = parseInt(hints_number.innerHTML) + 1;
      hints_number.innerHTML = hints.toString();
    }
    // stop the timer to restart it with the new time
    inputClicked = 0;
    // show the hint_button again
    hint_button.style.display = "flex";

  } else if (incorrect_answer == "yes") {
    // in the case that it was an incorrect answer show the background in red
    setTimeout(function() {
      document.body.style.background = "darkred";
    }, 1);

    setTimeout(function() {
      document.body.style.background = "lightblue";
    }, 400);
    // reduce the points by 20 as it was an incorrect answer
    let points = parseInt(points_number.innerHTML) - 20;
    points_number.innerHTML = points.toString();
    error.style.display = 'block';
    error.innerHTML = "Incorrect Answer Try Again!";
  }
}

// function to generate a random number between minimum and maximum (including minimum but excluding maximum)
function get_random(minimum, maximum) {
  return Math.floor(Math.random() * (maximum - minimum)) + minimum;
}


// get the hint button
const hint_button = document.getElementById("hints");
var incorrect_index = [];
// if the hints button is clicked do this function
hint_button.onclick = function () {
    error.style.display = 'none';
    // if you have no more hints left then dont let the user
    // get a hint
    if(hints_number.innerHTML == '0'){
      error.style.display = 'block';
      error.innerHTML = "No Hints Left!";
      hint_button.style.display = "none";
      return false;
    }
    // reduce points by 100
    let pointsdeducted = parseInt(points_number.innerHTML) - 100;
    points_number.innerHTML = pointsdeducted.toString();
    // reduce the number of hints
    let hints = parseInt(hints_number.innerHTML) - 1;
    hints_number.innerHTML = hints.toString();
    // push it as an incorrect answer as the student could not get
    // the answer themself
    incorrect_index.push(0);
    // fill in the answer into the question
    document.getElementById("fill_answer").value = equation_answer[0];
    // dont let the user change the naswer
    document.getElementById("fill_answer").disabled = true;
    hint_button.style.display = "none";
}


// check is used to stop the timer when the time is right
var check = null;
// create the timer
function startTimer(dur) {
  var timer = dur,
    minutes,
    seconds;
  if (check == null) {
    check = setInterval(function () {
      minutes = parseInt(timer / 60, 10);
      seconds = parseInt(timer % 60, 10);
      minutes = minutes < 10 ? "0" + minutes : minutes;
      seconds = seconds < 10 ? "0" + seconds : seconds;

      if(minutes != 0){
      // Update the timer
      document.getElementById("timer").innerHTML = minutes + ":" + seconds;
      } else {
        if(seconds < 10){
          // remove the first 0 from the seconds
          seconds = seconds[1];
          // if the time is 3 seconds then make the  timer red to show so sense of urgency
          // the same with 5 seconds
          if (seconds <= 3){
            document.getElementById("timer").style.color = 'red';
          } else if(seconds <= 5){
            document.getElementById("timer").style.color = 'darkred';
          } else {
            document.getElementById("timer").style.color = 'black';
          }  
        }
        // Update the timer
        document.getElementById("timer").innerHTML = seconds;
      }
      // if the timer is less than 0
      if (--timer < 0) {
        // END GAME
        stopTimer();
        endGame();
      }
    }, 1000);
  }
}



// This is called at the end of the game (when the timer ends)
function endGame(){
  // show up the buttons
  upload.style.display = 'flex';
    if(document.getElementById("preview") == 'false'){
      retry.style.display = 'flex';
    }    
    
    // Here we check the percentage score the student got
    let percentage_score = Math.floor((correct.length / answers.length) * 100)
    if(answers.length == 0){
      score_value.innerHTML = correct.length + ' / ' + answers.length + ' (0%)';
    } else {
      score_value.innerHTML = correct.length + ' / ' + answers.length + ' (' + percentage_score + '%)';
    }
    if(correct.length >= min_no_questions){
      if(percentage_score >= pass_percentage){
        score_value.innerHTML += ' PASSED';
        passed_hidden.value = 'true';
      } else {
        score_value.innerHTML += ' FAILED';
        passed_hidden.value = 'false';
      }  
    } else {
      score_value.innerHTML += ' FAILED (not enough questions correct)';
      passed_hidden.value = 'false';
    }
    // set up all the hidden inputs to be sent off to the leaderboard
    score_hidden.value = percentage_score;
    points_hidden.value = parseInt(points_number.innerHTML);
    duration_hidden.value = parseInt(document.getElementById("duration").value);
    correct_ones.value = correct.length;
    incorrect_ones.value = incorrectAns.length;
    // HERE NEED TO CHECK IF ANSWER IS IN INCORRECT ON CORRECT AND IF IT IS IN CORRECT THEN SET ID FOR C TO BE CORRECT OR INCORRECT SO I CAN PUT COLOURED BORDER AROUND
    // Think it looks better if it shows the answers in chronological order (order player did them in) rather than correct ones first then incorrect ones 
    // But this means that the two for loops are needed so maybe I could change that around...
    for (let i = 0; i < answers.length; i++) {
      // this answer variable will keep creating the inputted answers to show up
      let answer = "";
      answer += "<p>" + (i+1) + ') ';
      for (let j = 0; j < answers[i].length; j++) {
        if(answers[i][j].slice(-1) == "C"){
          // do something and make it green square
          answer += ` <input type="number" id="fill_answer_correct" value="${answers[i][j].slice(0, answers[i][j].length - 1)}" readonly\> `;
        } else if (answers[i][j].slice(-1) == "I"){
          // do something and make it red square
          answer += ` <input type="number" id="fill_answer_incorrect" value="${answers[i][j].slice(0, answers[i][j].length - 1)}" readonly\> `;
        } else {
          answer += ' ' + answers[i][j] + ' ';
        }
      }
      // add this answer to the screen
      present_answers.innerHTML += answer;
      present_answers.innerHTML += '</p><br>';
    }
    // Once that is all done remove the last 4 characters in string (</p><br>)
    present_answers.innerHTML = present_answers.innerHTML.slice(0, -4);
    // hide and show the right things
    question_box.style.display = 'none';
    score.style.display = 'flex';
    present_answers.style.display = 'block';
    show_results.style.display = 'block';
    results_box.style.display = 'flex';
}

// Function to stop the timer running
function stopTimer() {
  // use the function clearInterval to stop timer
  clearInterval(check);
  check = null;
  document.getElementById("timer").innerHTML = "05:00";
}

//When the game loads up do this
window.onload = function() {
  // set the variables
  difficulty = document.getElementById("difficulty").value;
  var duration = parseInt(document.getElementById("duration").value);
  duration = (duration * 60) - 1;
  operators_chosen = document.getElementById("operators").value.split(", ");
  for (let index = 0; index < operators_chosen.length; index++) {
    if(operators_chosen[index] == 'add'){
      operators_chosen[index] = '+';
    } else if(operators_chosen[index] == 'minus'){
      operators_chosen[index] = '-';
    } else if(operators_chosen[index] == 'div'){
      operators_chosen[index] = '/';
    } else if(operators_chosen[index] == 'mult'){
      operators_chosen[index] = '*';
    }
  }
  pass_percentage = parseInt(document.getElementById("pass_percentage").value);
  min_no_questions = parseInt(document.getElementById("min_no_questions").value);
  points_number.innerHTML = "0";
  // Timer starts at duration - 1 to account for the second delay
  startTimer(duration);
  // Load up the first question
  load_question();
};