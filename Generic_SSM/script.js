var checked_answer = null;
var checked_pressed = null;
var stop_checking = false;


const button_a = document.getElementById("a");
const button_b = document.getElementById("b");
const button_c = document.getElementById("c");
const button_d = document.getElementById("d");
const lifelines = document.getElementById("lifelines_used");
const questions_correct = document.getElementById("questions_right");
const res = document.getElementById("result");
const check_answer = document.getElementById("check");
const fifty_fifty = document.getElementById("fiddyfiddy");
const hinting = document.getElementById("hints");



// Add event listener to check answer 
// it will check if the answer is correct or not
// If the answer is incorrect then remove the button and say it was incorrect
// If the answer is correct then go to the next question by clicking the button
// Then remove the lifeline image
check_answer.addEventListener("click", function () {
  if (stop_checking == true) {
    return;
  }
  checked_pressed = true;
  // Prompt user to click second button
  alert("Please now select a question to check");

  // check if answer a was clicked
  button_a.addEventListener("click", function () {
    if (stop_checking == true) {
      return;
    }
    stop_checking = true;
    
    if (
      button_a.innerHTML.substring(3) !=
      correctans[currentIndex - 1]
    ) {
      alert("incorrect answer");
      button_a.style.visibility = "hidden";
    } else {
      alert("correct!");
      button_a.click();
    }
    checked_answer = button_a;
    check_answer.style.display = "none";
    return;
  });

  // check if answer b was clicked
  button_b.addEventListener("click", function () {
    if (stop_checking == true) {
      return;
    }
    stop_checking = true;
    if (
      button_b.innerHTML.substring(3) !=
      correctans[currentIndex - 1]
    ) {
      alert("incorrect answer");
      button_b.style.visibility = "hidden";
    } else {
      alert("correct!");
      button_b.click();
    }
    checked_answer = button_b;
    check_answer.style.display = "none";
    return;
  });

  // check if answer c was clicked
  button_c.addEventListener("click", function () {
    if (stop_checking == true) {
      return;
    }
    stop_checking = true;
    if (
      button_c.innerHTML.substring(3) !=
      correctans[currentIndex - 1]
    ) {
      alert("incorrect answer");
      button_c.style.visibility = "hidden";
    } else {
      alert("correct!");
      button_c.click();
    }
    checked_answer = button_c;
    check_answer.style.display = "none";
    return;
  });

  // check if answer d was clicked
  button_d.addEventListener("click", function () {
    if (stop_checking == true) {
      return;
    }
    stop_checking = true;
    if (
      button_d.innerHTML.substring(3) !=
      correctans[currentIndex - 1]
    ) {
      alert("incorrect answer");
      button_d.style.visibility = "hidden";
    } else {
      alert("correct!");
      button_d.click();
    }
    checked_answer = button_d;
    check_answer.style.display = "none";

    return;
  });
});

// this will hold all the clicked lifenes
lifelines_removed = [];
already_on_previous_button = false;
endgame = false;
// This function checks if one of the buttons has been clicked to check previous anaswer
function goBack(clicked_button_id) {
  if (already_on_previous_button == true) {
    button_a.style.backgroundColor = "white";
    button_b.style.backgroundColor = "white";
    button_c.style.backgroundColor = "white";
    button_d.style.backgroundColor = "white";
    already_on_previous_button = false;
  }

  if (clicked_button_id == "current") {
    if (wrong_button != null) {
      wrong_button.style.backgroundColor = "lightcoral";
      right_button.style.backgroundColor = "lightgreen";
      questionNumberSpan.innerHTML = index;
      question.innerHTML = "Q) " + questions[currentIndex - 1];
      button_a.innerHTML = "A) " + ans1[currentIndex - 1];
      button_b.innerHTML = "B) " + ans2[currentIndex - 1];
      button_c.innerHTML = "C) " + ans3[currentIndex - 1];
      button_d.innerHTML = "D) " + ans4[currentIndex - 1];
      button_a.disabled = true;
      button_b.disabled = true;
      button_c.disabled = true;
      button_d.disabled = true;
      document.getElementById("current").classList.remove("show");
      document.getElementById("current").classList.add("hide");
      already_on_previous_button = false;
      // for (let index = 0; index < lifelines_removed.length; index++) {
      //   const element = lifelines_removed[index];
      //   document.getElementById(element).style.display = "inline";
      // }
    } else {
      questionNumberSpan.innerHTML = index;
      question.innerHTML = "Q) " + questions[currentIndex - 1];
      button_a.innerHTML = "A) " + ans1[currentIndex - 1];
      button_b.innerHTML = "B) " + ans2[currentIndex - 1];
      button_c.innerHTML = "C) " + ans3[currentIndex - 1];
      button_d.innerHTML = "D) " + ans4[currentIndex - 1];
      button_a.disabled = false;
      button_b.disabled = false;
      button_c.disabled = false;
      button_d.disabled = false;
      document.getElementById("current").classList.remove("show");
      document.getElementById("current").classList.add("hide");
      already_on_previous_button = false;
      for (let index = 0; index < lifelines_removed.length; index++) {
        const element = lifelines_removed[index];
        document.getElementById(element).style.display = "inline";
      }
    }
  } else {
    if (wrong_button != null) {
      wrong_button.style.backgroundColor = "white";
      right_button.style.backgroundColor = "white";
    }
    document.getElementById("current").classList.remove("hide");
    document.getElementById("current").classList.add("show");
    clicked_button_id = parseInt(clicked_button_id);
    questionNumberSpan.innerHTML = clicked_button_id;
    question.innerHTML = "Q) " + questions[clicked_button_id - 1];
    button_a.innerHTML =
      "A) " + ans1[clicked_button_id - 1];
    button_b.innerHTML =
      "B) " + ans2[clicked_button_id - 1];
    button_c.innerHTML =
      "C) " + ans3[clicked_button_id - 1];
    button_d.innerHTML =
      "D) " + ans4[clicked_button_id - 1];

    button_a.disabled = true;
    button_b.disabled = true;
    button_c.disabled = true;
    button_d.disabled = true;
    if (
      button_a.innerHTML.substring(3) ==
      correctans[clicked_button_id - 1]
    ) {
      button_a.style.backgroundColor = "lightgreen";
      button_b.style.backgroundColor = "lightcoral";
      button_c.style.backgroundColor = "lightcoral";
      button_d.style.backgroundColor = "lightcoral";
    } else if (
      button_b.innerHTML.substring(3) ==
      correctans[clicked_button_id - 1]
    ) {
      button_b.style.backgroundColor = "lightgreen";
      button_a.style.backgroundColor = "lightcoral";
      button_c.style.backgroundColor = "lightcoral";
      button_d.style.backgroundColor = "lightcoral";
    } else if (
      button_c.innerHTML.substring(3) ==
      correctans[clicked_button_id - 1]
    ) {
      button_c.style.backgroundColor = "lightgreen";
      button_a.style.backgroundColor = "lightcoral";
      button_b.style.backgroundColor = "lightcoral";
      button_d.style.backgroundColor = "lightcoral";
    } else if (
      button_d.innerHTML.substring(3) ==
      correctans[clicked_button_id - 1]
    ) {
      button_d.style.backgroundColor = "lightgreen";
      button_a.style.backgroundColor = "lightcoral";
      button_b.style.backgroundColor = "lightcoral";
      button_c.style.backgroundColor = "lightcoral";
    }
    already_on_previous_button = true;
    if (hinting.style.display != "none") {
      lifelines_removed.push("hints");
      hinting.style.display = "none";
    }
    if (check_answer.style.display != "none") {
      lifelines_removed.push("check");
      check_answer.style.display = "none";
    }
    if (fifty_fifty.style.display != "none") {
      lifelines_removed.push("fiddyfiddy");
      fifty_fifty.style.display = "none";
    }
  }
}

// This is the function that is called when the hint button is clicked
// It removes the image and shows the hint below the question
function show_hint() {
  document.getElementById("hint_id").style.display = "inline";
  document.getElementById("hint_id").value = hinters[currentIndex - 1];
  hinting.style.display = "none";
}

var textbox_ids = [];

// This is called when the 50/50 lifeline is clicked
// It checks what the correct answer is and then gets all the remaining answer
// Shuffles them around and randomly removes 2 answers.
function fiftyfifty() {
  if (
    correctans[currentIndex - 1] ==
    button_a.innerHTML.substring(3)
  ) {
    list_of_ids = ["b", "c", "d"];
  } else if (
    correctans[currentIndex - 1] ==
    button_b.innerHTML.substring(3)
  ) {
    list_of_ids = ["a", "c", "d"];
  } else if (
    correctans[currentIndex - 1] ==
    button_c.innerHTML.substring(3)
  ) {
    list_of_ids = ["a", "b", "d"];
  } else if (
    correctans[currentIndex - 1] ==
    button_d.innerHTML.substring(3)
  ) {
    list_of_ids = ["a", "b", "c"];
  }
  const shuffled = list_of_ids.sort(() => 0.5 - Math.random());

  // Get sub-array of first n elements after shuffled
  let selected = shuffled.slice(0, 2);

  for (let index = 0; index < selected.length; index++) {
    document.getElementById(selected[index]).style.visibility = "hidden";
  }
  fifty_fifty.style.display = "none";
}

// Generate all the variabels needed
const questionNumberSpan = document.querySelector(".questNum");
const question = document.querySelector(".question");
const totalQuestionsSpan = document.querySelector(".totQuest");

let currentIndex = 0;
let index = 0;
var answeredQuestions = []; // array of anwered question indexes
var score = 0;

let quest;
let an1;
let an2;
let an3;
let an4;
let corr;
let hintz;

var questions;
var ans1;
var ans2;
var ans3;
var ans4;
var correctans;
var hinters;
var hints;

// Here the timer is created 
function timer(dur) {
  var timer = dur,
    minutes,
    seconds;
  if (check == null) {
    check = setInterval(
      function () {
        // Get the minutes and seconds
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);
        // If either is less than ten then adda  0 to it
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;
        // If the seconds are less than 60 then do this
        if (timer < 60) {
          // if timer is less than 10 seconds then just show the individual number not 0X seconds just X seconds
          if (seconds < 10) {
            document.getElementById("timer").innerHTML = seconds[1];
          } else {
            document.getElementById("timer").innerHTML = seconds;
          }
        } else {
          document.getElementById("timer").innerHTML = minutes + ":" + seconds;
        }
        // if timer is less than 0 seconds end game
        if (--timer < 0) {
          if (currentIndex - 1 != questions.length) {
            if (
              button_a.innerHTML.substring(3) ==
              correctans[currentIndex - 1]
            ) {
              button_a.style.backgroundColor = "lightgreen";
              button_b.style.backgroundColor = "lightcoral";
              button_c.style.backgroundColor = "lightcoral";
              button_d.style.backgroundColor = "lightcoral";
            } else if (
              button_b.innerHTML.substring(3) ==
              correctans[currentIndex - 1]
            ) {
              button_b.style.backgroundColor = "lightgreen";
              button_a.style.backgroundColor = "lightcoral";
              button_c.style.backgroundColor = "lightcoral";
              button_d.style.backgroundColor = "lightcoral";
            } else if (
              button_c.innerHTML.substring(3) ==
              correctans[currentIndex - 1]
            ) {
              button_c.style.backgroundColor = "lightgreen";
              button_a.style.backgroundColor = "lightcoral";
              button_b.style.backgroundColor = "lightcoral";
              button_d.style.backgroundColor = "lightcoral";
            } else if (
              button_d.innerHTML.substring(3) ==
              correctans[currentIndex - 1]
            ) {
              button_d.style.backgroundColor = "lightgreen";
              button_a.style.backgroundColor = "lightcoral";
              button_b.style.backgroundColor = "lightcoral";
              button_c.style.backgroundColor = "lightcoral";
            }
            if (hinting.style.display != "none") {
              hinting.disabled = true;
            }
            if (check_answer.style.display != "none") {
              check_answer.disabled = true;
            }
            if (fifty_fifty.style.display != "none") {
              fifty_fifty.disabled = true;
            }
          }

          buttonCounter = questions.length;
          checkAnswers();
          quizOver();
          stopTimer();
          return;
        }
      },
      1000,
      1
    );
  }
}

var check = null;

// Stop the timer by clearing check and setting it to null;
function stopTimer() {
  clearInterval(check);
  check = null;
}

var questionCounter = 0;

// This funciton loads the question
function load() {
  // If it is the last question then end the quiz else
  if (questionCounter == questions.length) {
    checkAnswers();
    quizOver();
    return;
  }
  // generate the new question
  questionNumberSpan.innerHTML = index + 1;
  question.innerHTML = "Q) " + questions[currentIndex];
  button_a.innerHTML = "A) " + ans1[currentIndex];
  button_b.innerHTML = "B) " + ans2[currentIndex];
  button_c.innerHTML = "C) " + ans3[currentIndex];
  button_d.innerHTML = "D) " + ans4[currentIndex];
  index++;
  currentIndex++;
  questionCounter++;
}

var point_total = 0;
var wrong_button = null;
var right_button = null;

// validate the anser that was clicked
function validate(button_clicked_id) {
  checked_answer = null;
  var button = document.getElementById(button_clicked_id);
  button_a.style.visibility = "visible";
  button_b.style.visibility = "visible";
  button_c.style.visibility = "visible";
  button_d.style.visibility = "visible";
  const rectangles = document.getElementsByClassName("rectangle");
  if (button.innerHTML.substring(3) != correctans[currentIndex - 1]) {
    stopTimer();
    wrong_button = button;
    if (
      button_a.innerHTML.substring(3) ==
      correctans[currentIndex - 1]
    ) {
      button_a.style.backgroundColor = "lightgreen";
      button_b.style.backgroundColor = "lightcoral";
      button_c.style.backgroundColor = "lightcoral";
      button_d.style.backgroundColor = "lightcoral";
      button_a.disabled = true;
      button_b.disabled = true;
      button_c.disabled = true;
      button_d.disabled = true;

      right_button = button_a;
    } else if (
      button_b.innerHTML.substring(3) ==
      correctans[currentIndex - 1]
    ) {
      button_b.style.backgroundColor = "lightgreen";
      button_a.style.backgroundColor = "lightcoral";
      button_c.style.backgroundColor = "lightcoral";
      button_d.style.backgroundColor = "lightcoral";
      button_a.disabled = true;
      button_b.disabled = true;
      button_c.disabled = true;
      button_d.disabled = true;

      right_button = button_b;
    } else if (
      button_c.innerHTML.substring(3) ==
      correctans[currentIndex - 1]
    ) {
      button_c.style.backgroundColor = "lightgreen";
      button_a.style.backgroundColor = "lightcoral";
      button_b.style.backgroundColor = "lightcoral";
      button_d.style.backgroundColor = "lightcoral";
      button_a.disabled = true;
      button_b.disabled = true;
      button_c.disabled = true;
      button_d.disabled = true;

      right_button = button_c;
    } else if (
      button_d.innerHTML.substring(3) ==
      correctans[currentIndex - 1]
    ) {
      button_d.style.backgroundColor = "lightgreen";
      button_a.style.backgroundColor = "lightcoral";
      button_b.style.backgroundColor = "lightcoral";
      button_c.style.backgroundColor = "lightcoral";
      button_a.disabled = true;
      button_b.disabled = true;
      button_c.disabled = true;
      button_d.disabled = true;

      right_button = button_d;
    }
    if (hinting.style.display != "none") {
      hinting.disabled = true;
    }
    if (check_answer.style.display != "none") {
      check_answer.disabled = true;
    }
    if (fifty_fifty.style.display != "none") {
      fifty_fifty.disabled = true;
    }

    rectangles[rectangles.length - currentIndex].style.backgroundColor = "red";
    checkAnswers();
    return;
  }

  if (rectangles[11].style.backgroundColor == "yellow") {
    rectangles[11].style.backgroundColor = "lightgreen";
    rectangles[10].style.backgroundColor = "yellow";
    point_total = parseInt(rectangles[11].innerHTML.replace(/,/g, ""));
    document.getElementById("1").classList.remove("hide");
    document.getElementById("1").classList.add("show");
    var time = document.getElementById("timer").innerHTML;
    stopTimer();
    time = time.split(":");
    if (time.length > 1) {
      var mins = parseInt(time[0]);
      var secs = parseInt(time[1]);
      var fulltime = mins * 60 + secs + 15;
    } else {
      secs = parseInt(time);
      fulltime = secs + 15;
    }
    timer(fulltime);
  } else if (rectangles[10].style.backgroundColor == "yellow") {
    rectangles[10].style.backgroundColor = "lightgreen";
    rectangles[9].style.backgroundColor = "yellow";
    point_total = parseInt(rectangles[10].innerHTML.replace(/,/g, ""));
    document.getElementById("2").classList.remove("hide");
    document.getElementById("2").classList.add("show");
    var time = document.getElementById("timer").innerHTML;
    stopTimer();
    time = time.split(":");
    if (time.length > 1) {
      var mins = parseInt(time[0]);
      var secs = parseInt(time[1]);
      var fulltime = mins * 60 + secs + 15;
    } else {
      secs = parseInt(time);
      fulltime = secs + 15;
    }
    timer(fulltime);
  } else if (rectangles[9].style.backgroundColor == "yellow") {
    rectangles[9].style.backgroundColor = "lightgreen";
    rectangles[8].style.backgroundColor = "yellow";
    point_total = parseInt(rectangles[9].innerHTML.replace(/,/g, ""));
    document.getElementById("3").classList.remove("hide");
    document.getElementById("3").classList.add("show");
    var time = document.getElementById("timer").innerHTML;
    stopTimer();
    time = time.split(":");
    if (time.length > 1) {
      var mins = parseInt(time[0]);
      var secs = parseInt(time[1]);
      var fulltime = mins * 60 + secs + 15;
    } else {
      secs = parseInt(time);
      fulltime = secs + 15;
    }
    timer(fulltime);
  } else if (rectangles[8].style.backgroundColor == "yellow") {
    rectangles[8].style.backgroundColor = "lightgreen";
    rectangles[7].style.backgroundColor = "yellow";
    point_total = parseInt(rectangles[8].innerHTML.replace(/,/g, ""));
    document.getElementById("4").classList.remove("hide");
    document.getElementById("4").classList.add("show");
    var time = document.getElementById("timer").innerHTML;
    stopTimer();
    time = time.split(":");
    if (time.length > 1) {
      var mins = parseInt(time[0]);
      var secs = parseInt(time[1]);
      var fulltime = mins * 60 + secs + 15;
    } else {
      secs = parseInt(time);
      fulltime = secs + 15;
    }
    timer(fulltime);
  } else if (rectangles[7].style.backgroundColor == "yellow") {
    rectangles[7].style.backgroundColor = "lightgreen";
    rectangles[6].style.backgroundColor = "yellow";
    point_total = parseInt(rectangles[7].innerHTML.replace(/,/g, ""));
    document.getElementById("5").classList.remove("hide");
    document.getElementById("5").classList.add("show");
    var time = document.getElementById("timer").innerHTML;
    stopTimer();
    time = time.split(":");
    if (time.length > 1) {
      var mins = parseInt(time[0]);
      var secs = parseInt(time[1]);
      var fulltime = mins * 60 + secs + 15;
    } else {
      secs = parseInt(time);
      fulltime = secs + 15;
    }
    timer(fulltime);
  } else if (rectangles[6].style.backgroundColor == "yellow") {
    rectangles[6].style.backgroundColor = "lightgreen";
    rectangles[5].style.backgroundColor = "yellow";
    point_total = parseInt(rectangles[6].innerHTML.replace(/,/g, ""));
    document.getElementById("6").classList.remove("hide");
    document.getElementById("6").classList.add("show");
    var time = document.getElementById("timer").innerHTML;
    stopTimer();
    time = time.split(":");
    if (time.length > 1) {
      var mins = parseInt(time[0]);
      var secs = parseInt(time[1]);
      var fulltime = mins * 60 + secs + 15;
    } else {
      secs = parseInt(time);
      fulltime = secs + 15;
    }
    timer(fulltime);
  } else if (rectangles[5].style.backgroundColor == "yellow") {
    rectangles[5].style.backgroundColor = "lightgreen";
    rectangles[4].style.backgroundColor = "yellow";
    point_total = parseInt(rectangles[5].innerHTML.replace(/,/g, ""));
    document.getElementById("7").classList.remove("hide");
    document.getElementById("7").classList.add("show");
    var time = document.getElementById("timer").innerHTML;
    stopTimer();
    time = time.split(":");
    if (time.length > 1) {
      var mins = parseInt(time[0]);
      var secs = parseInt(time[1]);
      var fulltime = mins * 60 + secs + 15;
    } else {
      secs = parseInt(time);
      fulltime = secs + 15;
    }
    timer(fulltime);
  } else if (rectangles[4].style.backgroundColor == "yellow") {
    rectangles[4].style.backgroundColor = "lightgreen";
    rectangles[3].style.backgroundColor = "yellow";
    point_total = parseInt(rectangles[4].innerHTML.replace(/,/g, ""));
    document.getElementById("8").classList.remove("hide");
    document.getElementById("8").classList.add("show");
    var time = document.getElementById("timer").innerHTML;
    stopTimer();
    time = time.split(":");
    if (time.length > 1) {
      var mins = parseInt(time[0]);
      var secs = parseInt(time[1]);
      var fulltime = mins * 60 + secs + 15;
    } else {
      secs = parseInt(time);
      fulltime = secs + 15;
    }
    timer(fulltime);
  } else if (rectangles[3].style.backgroundColor == "yellow") {
    rectangles[3].style.backgroundColor = "lightgreen";
    rectangles[2].style.backgroundColor = "yellow";
    point_total = parseInt(rectangles[3].innerHTML.replace(/,/g, ""));
    document.getElementById("9").classList.remove("hide");
    document.getElementById("9").classList.add("show");
    var time = document.getElementById("timer").innerHTML;
    stopTimer();
    time = time.split(":");
    if (time.length > 1) {
      var mins = parseInt(time[0]);
      var secs = parseInt(time[1]);
      var fulltime = mins * 60 + secs + 15;
    } else {
      secs = parseInt(time);
      fulltime = secs + 15;
    }
    timer(fulltime);
  } else if (rectangles[2].style.backgroundColor == "yellow") {
    rectangles[2].style.backgroundColor = "lightgreen";
    rectangles[1].style.backgroundColor = "yellow";
    point_total = parseInt(rectangles[2].innerHTML.replace(/,/g, ""));
    document.getElementById("10").classList.remove("hide");
    document.getElementById("10").classList.add("show");
    var time = document.getElementById("timer").innerHTML;
    stopTimer();
    time = time.split(":");
    if (time.length > 1) {
      var mins = parseInt(time[0]);
      var secs = parseInt(time[1]);
      var fulltime = mins * 60 + secs + 15;
    } else {
      secs = parseInt(time);
      fulltime = secs + 15;
    }
    timer(fulltime);
  } else if (rectangles[1].style.backgroundColor == "yellow") {
    rectangles[1].style.backgroundColor = "lightgreen";
    rectangles[0].style.backgroundColor = "yellow";
    point_total = parseInt(rectangles[1].innerHTML.replace(/,/g, ""));
    document.getElementById("11").classList.remove("hide");
    document.getElementById("11").classList.add("show");
    var time = document.getElementById("timer").innerHTML;
    stopTimer();
    time = time.split(":");
    if (time.length > 1) {
      var mins = parseInt(time[0]);
      var secs = parseInt(time[1]);
      var fulltime = mins * 60 + secs + 15;
    } else {
      secs = parseInt(time);
      fulltime = secs + 15;
    }
    timer(fulltime);
  } else if (rectangles[0].style.backgroundColor == "yellow") {
    rectangles[0].style.backgroundColor = "lightgreen";
    point_total = parseInt(rectangles[0].innerHTML.replace(/,/g, ""));
    document.getElementById("12").classList.remove("hide");
    document.getElementById("12").classList.add("show");
    quizOver();
    stopTimer();
    // FIREWORKS GO OFF!!!! YOU WIN!!!!
    // GET BADGE: MILLIONAIRE, GET LEADERBOARD BADGE, etc...
  }

  if (checked_answer != null) {
    checked_answer.style.display = "inline";
  }
  document.getElementById("hint_id").style.display = "none";

  answeredQuestions.push(button.innerHTML.substring(3));

  load();
  return;
}

var topic;
var subject;
var difficulty;
var buttonCounter = 0;
function next(clicked_button_id) {
  if (checked_pressed == true) {
    checked_pressed = false;
    return;
  }
  var button = document.getElementById(clicked_button_id);
  if (buttonCounter == questions.length) {
    return;
  }
  buttonCounter++;
  validate(clicked_button_id);
}
window.onload = function () {
  var api_key = config.apiKeyGPT;
  const model = "text-davinci-003";

  // Set the prompt to generate quiz questions and answers from
  const urlParams = new URLSearchParams(window.location.search);
  difficulty = urlParams.get("difficulty");

  topic = urlParams.get("topic");
  topic = topic.replace(/_/g, " ");

  subject = urlParams.get("subject");
  subject = subject.replace(/_/g, " ");

  var prompt = `Please create an ${difficulty} difficulty Who Wants To Be A Millionaire style a quiz on the topic of ${topic} for kids aged 11-16 with 12 questions each with 4 potential answers with one being the correct one and a hint (which is not the answer) for each question and format it as a JSON object that contains an array of quiz questions and answers and the questions get increasing harder. The attributes in this JSON object are: Quiz: The main object containing an array of quiz questions and answers, Question: The text of the question, Answers: An array of potential answers for the question, CorrectAnswer: The correct answer for the question which is word for word the same as the one in the Answers array, Hint: A hint related to the question, Each element in the Quiz array contains an object that has Question, Answers, CorrectAnswer, and Hint keys that have corresponding values as strings. These keys are the attributes/fields of the JSON object, which holds values of quiz questions, answers, correct answer and hint respectively`;
  const arr = topic.split(" ");

  //loop through each element of the array and capitalize the first letter.
  for (var i = 0; i < arr.length; i++) {
    arr[i] = arr[i].charAt(0).toUpperCase() + arr[i].slice(1);
  }

  //Join all the elements of the array back into a string
  //using a blankspace as a separator
  const str2 = arr.join(" ");
  document.getElementById("title").innerHTML =
    "SUBJECT SAVVY MILLIONAIRE: " + str2;

  const data = {
    model: model,
    prompt: prompt,
    max_tokens: 2048,
    n: 1,
    stop: null,
    temperature: 0.5,
  };

  // Set up the request options
  const options = {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Authorization: `Bearer ${api_key}`,
    },
    body: JSON.stringify(data),
  };

  async function fetchData() {
    try {
      loader = document.getElementById("loading-overlay");
      loader.style.display = "block";

      const response = await fetch(
        "https://api.openai.com/v1/completions",
        options
      );
      const jsonResponse = await response.json();
      const questions_answers = jsonResponse.choices[0].text;    
      try {
        let jsonString = questions_answers.slice(1);
        let jsonObject = JSON.parse(jsonString);     

        if (!jsonObject) {
          console.log("Not a valid JSON");
        }
        ques = [];
        ans1 = [];
        ans2 = [];
        ans3 = [];
        ans4 = [];
        correctans = [];
        hints = [];
        for (let index = 0; index < jsonObject.Quiz.length; index++) {
          ques.push(jsonObject.Quiz[index].Question);
          hints.push(jsonObject.Quiz[index].Hint);
          correctans.push(jsonObject.Quiz[index].CorrectAnswer);
          ans1.push(jsonObject.Quiz[index].Answers[0]);
          ans2.push(jsonObject.Quiz[index].Answers[1]);
          ans3.push(jsonObject.Quiz[index].Answers[2]);
          ans4.push(jsonObject.Quiz[index].Answers[3]);
        }
        document.getElementById("hiddenquestion").value = ques.join("_");
        document.getElementById("hiddenanswer1").value = ans1.join("_");
        document.getElementById("hiddenanswer2").value = ans2.join("_");
        document.getElementById("hiddenanswer3").value = ans3.join("_");
        document.getElementById("hiddenanswer4").value = ans4.join("_");
        document.getElementById("hiddencorrectanswer").value =
          correctans.join("_");
        document.getElementById("hiddenhint").value = hints.join("_");

        quest = document.getElementById("hiddenquestion").value;
        an1 = document.getElementById("hiddenanswer1").value;
        an2 = document.getElementById("hiddenanswer2").value;
        an3 = document.getElementById("hiddenanswer3").value;
        an4 = document.getElementById("hiddenanswer4").value;
        corr = document.getElementById("hiddencorrectanswer").value;
        hintz = document.getElementById("hiddenhint").value;

        questions = quest.split("_");
        ans1 = an1.split("_");
        ans2 = an2.split("_");
        ans3 = an3.split("_");
        ans4 = an4.split("_");
        correctans = corr.split("_");
        hinters = hintz.split("_");

        totalQuestionsSpan.innerHTML = questions.length;
        const rectangles = document.getElementsByClassName("rectangle");
        rectangles[11].style.backgroundColor = "yellow";
        loader.style.display = "none";
      } catch (e) {
        console.log(e);
        loader.style.display = "none";
        location.href = "../login.php";
      }
    } catch (err) {
      console.error(err);
      loader.style.display = "none";
      location.href = "../student/student_homepage.php";
    }
    this.load();
    timer(14);
  }

  fetchData();
};

function checkAnswers() {
  for (var i = 0; i < answeredQuestions.length; i++) {
    if (answeredQuestions[i] == correctans[i]) {
      score++;
    }
  }
  quizOver();
}
var counter = 0;

function quizOver() {
  if (counter > 1) {
    return;
  }
  var list = document.querySelectorAll("input[type=hidden]");
  for (let index = 0; index < list.length; index++) {
    list[index].value = "";
  }

  document.getElementById("points").value = point_total;
  var count = 0;
  var lifelines_used = [];
  if (check_answer.style.display == "none") {
    count += 1;
    lifelines_used.push("check");
  }
  if (hinting.style.display == "none") {
    count += 1;
    lifelines_used.push("hint");
  }
  if (fifty_fifty.style.display == "none") {
    count += 1;
    lifelines_used.push("50/50");
  }
  if (count == 0) {
    lifelines_used.push("none");
  }
  lifelines.value =
    JSON.stringify(lifelines_used);
  questions_correct.value = score;
  document.getElementById("topic").value = topic;
  document.getElementById("subject").value = subject;
  document.getElementById("difficulty").value = difficulty;
  document.getElementById;
  res.style.display = "inline";
  document.getElementById("result_score").innerHTML = `${score}` + "/12";
  document.getElementById("pointer").innerHTML =
    "Points: " + point_total.toString();
  counter++;
}
