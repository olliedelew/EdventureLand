var lifelines_used = [];
var checked_answer = null;
var checked_pressed = null;
var stop_checking = false;

const button_a = document.getElementById("a");
const button_b = document.getElementById("b");
const button_c = document.getElementById("c");
const button_d = document.getElementById("d");
const lifelines_clicked = document.getElementById("lifelines_used");
const questions_correct = document.getElementById("questions_right");
const tot_quest = document.getElementById("total_questions");
const res = document.getElementById("result");
const check_answer = document.getElementById("check");
const fifty_fifty = document.getElementById("fiddyfiddy");
const hints = document.getElementById("hints");


// Add event listener to first button
check_answer.addEventListener("click", function() {
    lifelines_used.push("check");
    if(stop_checking == true){
        return;
    }
    checked_pressed = true;
    // Prompt user to click second button
    alert("Please now select a question to check");

    // Add event listener to second button
    button_a.addEventListener("click", function() {
        if(stop_checking == true){
        return;
        }
        stop_checking = true;
        if (button_a.innerHTML.substring(3) != correctans[currentIndex - 1]) {
        alert('incorrect answer');
        button_a.style.visibility = "hidden";
        } else {
            alert('correct!');
            button_a.click();
        }
        checked_answer = button_a;
        check_answer.style.display = 'none';
        return;
    });
    button_b.addEventListener("click", function() {
        if(stop_checking == true){
        return;
        }
        stop_checking = true;
        if (button_b.innerHTML.substring(3) != correctans[currentIndex - 1]) {

        alert('incorrect answer');
        button_b.style.visibility = "hidden";
        } else {
            alert('correct!');
            button_b.click();
        }
        checked_answer = button_b;
        check_answer.style.display = 'none';
        return;
    });
    button_c.addEventListener("click", function() {
        if(stop_checking == true){
        return;
        }
        stop_checking = true;

        if (button_c.innerHTML.substring(3) != correctans[currentIndex - 1]) {
        alert('incorrect answer');
        button_c.style.visibility = "hidden";
        } else {
            alert('correct!');
            button_c.click();
        }
        checked_answer = button_c;
        check_answer.style.display = 'none';
        return;
    });
    button_d.addEventListener("click", function() {
        if(stop_checking == true){
        return;
        }
        stop_checking = true;

        if (button_d.innerHTML.substring(3) != correctans[currentIndex - 1]) {
          alert('incorrect answer');
        button_d.style.visibility = "hidden";
        } else {
            alert('correct!');
            button_d.click();
        }
        checked_answer = button_d;
        check_answer.style.display = 'none';
        
        return;
    });

});

lifelines_removed = []
already_on_previous_button = false;
endgame = false;
function goBack(clicked_button_id){
    if(already_on_previous_button == true){
      if (answeredQuestions.length == questions.length) {
      if (button_a.innerHTML.substring(3) == correctans[currentIndex - 1]) {
            button_a.style.backgroundColor = "lightgreen";
            button_b.style.backgroundColor = "lightcoral";
            button_c.style.backgroundColor = "lightcoral";
            button_d.style.backgroundColor = "lightcoral";
        } else if (button_b.innerHTML.substring(3) == correctans[currentIndex - 1]) {
            button_b.style.backgroundColor = "lightgreen";
            button_a.style.backgroundColor = "lightcoral";
            button_c.style.backgroundColor = "lightcoral";
            button_d.style.backgroundColor = "lightcoral";
        } else if (button_c.innerHTML.substring(3) == correctans[currentIndex - 1]) {
            button_c.style.backgroundColor = "lightgreen";
            button_a.style.backgroundColor = "lightcoral";
            button_b.style.backgroundColor = "lightcoral";
            button_d.style.backgroundColor = "lightcoral";
        } else if (button_d.innerHTML.substring(3) == correctans[currentIndex - 1]) {
            button_d.style.backgroundColor = "lightgreen";
            button_a.style.backgroundColor = "lightcoral";
            button_b.style.backgroundColor = "lightcoral";
            button_c.style.backgroundColor = "lightcoral";
        }
        if(hints.style.display == 'none'){
          hints.disabled = true;
        }
        if(check_answer.style.display == 'none'){
          check_answer.disabled = true;
        }
        if(fifty_fifty.style.display == 'none'){
          fifty_fifty.disabled = true;
        }

      } else{
          button_a.style.backgroundColor = "white";
          button_b.style.backgroundColor = "white";
          button_c.style.backgroundColor = "white";
          button_d.style.backgroundColor = "white";
          button_a.disabled = false;
          button_b.disabled = false;
          button_c.disabled = false;
          button_d.disabled = false;  
        }
        already_on_previous_button = false;
    }

    if(clicked_button_id == "current"){
      if(wrong_button != null){
        wrong_button.style.backgroundColor = "lightcoral";
        right_button.style.backgroundColor = "lightgreen";
          questionNumberSpan.innerHTML = index
      question.innerHTML = 'Q) ' + questions[currentIndex-1];
        button_a.innerHTML = 'A) ' + ans1[currentIndex-1];
        button_b.innerHTML = 'B) ' + ans2[currentIndex-1];
        button_c.innerHTML = 'C) ' + ans3[currentIndex-1];
        button_d.innerHTML = 'D) ' + ans4[currentIndex-1];
        button_a.disabled = true;
        button_b.disabled = true;
        button_c.disabled = true;
        button_d.disabled = true;
    document.getElementById("current").classList.remove("show");
    document.getElementById("current").classList.add("hide");
    already_on_previous_button = false;
    for (let index = 0; index < lifelines_removed.length; index++) {
      const element = lifelines_removed[index];
      document.getElementById(element).style.display = "inline";
    }
      } else {
      questionNumberSpan.innerHTML = index
      question.innerHTML = 'Q) ' + questions[currentIndex-1];
      button_a.innerHTML = 'A) ' + ans1[currentIndex-1];
      button_b.innerHTML = 'B) ' + ans2[currentIndex-1];
      button_c.innerHTML = 'C) ' + ans3[currentIndex-1];
      button_d.innerHTML = 'D) ' + ans4[currentIndex-1];
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
      if(wrong_button != null){
        wrong_button.style.backgroundColor = "white";
        right_button.style.backgroundColor = "white";
      }
      document.getElementById("current").classList.remove("hide");
      document.getElementById("current").classList.add("show");
    clicked_button_id = parseInt(clicked_button_id);
    questionNumberSpan.innerHTML = clicked_button_id;
    question.innerHTML = 'Q) ' + questions[clicked_button_id-1];
    button_a.innerHTML = 'A) ' + ans1[clicked_button_id-1];
    button_b.innerHTML = 'B) ' + ans2[clicked_button_id-1];
    button_c.innerHTML = 'C) ' + ans3[clicked_button_id-1];
    button_d.innerHTML = 'D) ' + ans4[clicked_button_id-1];
    button_a.disabled = true;
    button_b.disabled = true;
    button_c.disabled = true;
    button_d.disabled = true;
  if (button_a.innerHTML.substring(3) == correctans[clicked_button_id - 1]) {
      button_a.style.backgroundColor = "lightgreen";
            button_b.style.backgroundColor = "lightcoral";
            button_c.style.backgroundColor = "lightcoral";
            button_d.style.backgroundColor = "lightcoral";

    } else if (button_b.innerHTML.substring(3) == correctans[clicked_button_id - 1]) {
      button_b.style.backgroundColor = "lightgreen";
            button_a.style.backgroundColor = "lightcoral";
            button_c.style.backgroundColor = "lightcoral";
            button_d.style.backgroundColor = "lightcoral";

    } else if (button_c.innerHTML.substring(3) == correctans[clicked_button_id - 1]) {
      button_c.style.backgroundColor = "lightgreen";
            button_a.style.backgroundColor = "lightcoral";
            button_b.style.backgroundColor = "lightcoral";
            button_d.style.backgroundColor = "lightcoral";
    } else if (button_d.innerHTML.substring(3) == correctans[clicked_button_id - 1]) {
      button_d.style.backgroundColor = "lightgreen";
            button_a.style.backgroundColor = "lightcoral";
            button_b.style.backgroundColor = "lightcoral";
            button_c.style.backgroundColor = "lightcoral";

    }
    already_on_previous_button = true;
    if(hints.style.display != 'none'){
      lifelines_removed.push("hints");
      hints.style.display = "none";
    }
    if(check_answer.style.display != 'none'){
      lifelines_removed.push("check");
      check_answer.style.display = "none";
    }
    if(fifty_fifty.style.display != 'none'){
      lifelines_removed.push("fiddyfiddy");
      fifty_fifty.style.display = "none";
    }
    }

}

function show_hint() {
    lifelines_used.push("hint");
    document.getElementById("hint_id").style.display = "inline"
    document.getElementById("hint_id").value = hinters[currentIndex - 1];
    hints.style.display = 'none';
    
}

var textbox_ids = [];

function fiftyfifty() {
    lifelines_used.push("50/50");
    if (correctans[currentIndex - 1] == button_a.innerHTML.substring(3)) {
        list_of_ids = ["b", "c", "d"]
    } else if (correctans[currentIndex - 1] == button_b.innerHTML.substring(3)) {
        list_of_ids = ["a", 'c', 'd']
    } else if (correctans[currentIndex - 1] == button_c.innerHTML.substring(3)) {
        list_of_ids = ['a', 'b', 'd']
    } else if (correctans[currentIndex - 1] == button_d.innerHTML.substring(3)) {
        list_of_ids = ['a', 'b', 'c']
    }
    const shuffled = list_of_ids.sort(() => 0.5 - Math.random());

    // Get sub-array of first n elements after shuffled
    let selected = shuffled.slice(0, 2);

    for (let index = 0; index < selected.length; index++) {
        document.getElementById(selected[index]).style.visibility = 'hidden';
    }
    fifty_fifty.style.display = 'none';

}


const questionNumberSpan = document.querySelector(".questNum")
const question = document.querySelector(".question")
const totalQuestionsSpan = document.querySelector(".totQuest")


let currentIndex = 0;
let index = 0;
var answeredQuestions = []; // array of anwered question indexes
var score = 0;
var pass_percentage = parseInt(document.getElementById('hiddenpasspercentage').value);

let quest = document.getElementById('hiddenquestion').value;
let an1 = document.getElementById('hiddenanswer1').value;
let an2 = document.getElementById('hiddenanswer2').value;
let an3 = document.getElementById('hiddenanswer3').value;
let an4 = document.getElementById('hiddenanswer4').value;
let corr = document.getElementById('hiddencorrectanswer').value;
let hintz = document.getElementById('hiddenhint').value;
let timez = document.getElementById('hiddentime').value;
let life = document.getElementById('hiddenlifelines').value;
lifelines = life.split("|");
for (let index = 0; index < lifelines.length; index++) {
const element = lifelines[index];
if(element == 'ask'){
    hints.style.display = 'inline';
} else if (element == 'life'){
    check_answer.style.display = "inline";
} else if (element == '5050'){
    fifty_fifty.style.display = "inline";
}
}
var questions = quest.split("|");
var ans1 = an1.split("|");
var ans2 = an2.split("|");
var ans3 = an3.split("|");
var ans4 = an4.split("|");
var correctans = corr.split("|");
var hinters = hintz.split("|");
var times = timez.split("|");
for (let index = 0; index < times.length; index++) {
times[index] = parseInt(times[index]);            
}

totalQuestionsSpan.innerHTML = questions.length

var tickCount = 0;
let duration = parseInt(times[0]);
function timer(dur) {
  var timer = dur,
    minutes,
    seconds;
  if (check == null) {
    check = setInterval(function() {
      minutes = parseInt(timer / 60, 10);
      seconds = parseInt(timer % 60, 10);

      minutes = minutes < 10 ? "0" + minutes : minutes;
      seconds = seconds < 10 ? "0" + seconds : seconds;
        if(timer < 60){
          if(seconds < 10){
            document.getElementById("timer").innerHTML = seconds[1];
          } else {
            document.getElementById("timer").innerHTML = seconds;
          }
        } else {
        document.getElementById("timer").innerHTML = minutes + ":" + seconds;
        }

        if (--timer < 0) {
            if((currentIndex-1) != questions.length){
                if (button_a.innerHTML.substring(3) == correctans[currentIndex - 1]) {
                  button_a.style.backgroundColor = "lightgreen";
            button_b.style.backgroundColor = "lightcoral";
            button_c.style.backgroundColor = "lightcoral";
            button_d.style.backgroundColor = "lightcoral";
                } else if (button_b.innerHTML.substring(3) == correctans[currentIndex - 1]) {
                  button_b.style.backgroundColor = "lightgreen";
            button_a.style.backgroundColor = "lightcoral";
            button_c.style.backgroundColor = "lightcoral";
            button_d.style.backgroundColor = "lightcoral";
                } else if (button_c.innerHTML.substring(3) == correctans[currentIndex - 1]) {
                  button_c.style.backgroundColor = "lightgreen";
            button_a.style.backgroundColor = "lightcoral";
            button_b.style.backgroundColor = "lightcoral";
            button_d.style.backgroundColor = "lightcoral";
                } else if (button_d.innerHTML.substring(3) == correctans[currentIndex - 1]) {
                  button_d.style.backgroundColor = "lightgreen";
            button_a.style.backgroundColor = "lightcoral";
            button_b.style.backgroundColor = "lightcoral";
            button_c.style.backgroundColor = "lightcoral";
                }

                if(hints.style.display != 'none'){
                  hints.disabled = true;
                }
                if(check_answer.style.display != 'none'){
                  check_answer.disabled = true;
                }
                if(fifty_fifty.style.display != 'none'){
                  fifty_fifty.disabled = true;
                }
            }

            buttonCounter = questions.length;
            checkAnswers();
            quizOver();
            stopTimer();
            return;
        }
    }, 1000, 1);
  }
}

var check = null;

function stopTimer() {
  clearInterval(check);
  check = null;
}


var questionCounter = 0;

function load() {
    if (questionCounter == questions.length) {
      if (button_a.innerHTML.substring(3) == correctans[currentIndex - 1]) {
            button_a.style.backgroundColor = "lightgreen";
            button_b.style.backgroundColor = "lightcoral";
            button_c.style.backgroundColor = "lightcoral";
            button_d.style.backgroundColor = "lightcoral";
        } else if (button_b.innerHTML.substring(3) == correctans[currentIndex - 1]) {
            button_b.style.backgroundColor = "lightgreen";
            button_a.style.backgroundColor = "lightcoral";
            button_c.style.backgroundColor = "lightcoral";
            button_d.style.backgroundColor = "lightcoral";
        } else if (button_c.innerHTML.substring(3) == correctans[currentIndex - 1]) {
            button_c.style.backgroundColor = "lightgreen";
            button_a.style.backgroundColor = "lightcoral";
            button_b.style.backgroundColor = "lightcoral";
            button_d.style.backgroundColor = "lightcoral";
        } else if (button_d.innerHTML.substring(3) == correctans[currentIndex - 1]) {
            button_d.style.backgroundColor = "lightgreen";
            button_a.style.backgroundColor = "lightcoral";
            button_b.style.backgroundColor = "lightcoral";
            button_c.style.backgroundColor = "lightcoral";
        }
        checkAnswers();
        quizOver();
        return;
    }
    questionNumberSpan.innerHTML = index + 1
    question.innerHTML = 'Q) ' + questions[currentIndex];
    button_a.innerHTML = 'A) ' + ans1[currentIndex];
    button_b.innerHTML = 'B) ' + ans2[currentIndex];
    button_c.innerHTML = 'C) ' + ans3[currentIndex];
    button_d.innerHTML = 'D) ' + ans4[currentIndex];

    index++;
    currentIndex++;
    questionCounter++;
}

var point_total = 0;
var wrong_button = null;
var right_button = null;

function validate(button_clicked_id) {
    checked_answer = null;
    var button = document.getElementById(button_clicked_id);
    button_a.style.visibility = "visible";
    button_b.style.visibility = "visible";
    button_c.style.visibility = "visible";
    button_d.style.visibility = "visible";
    if(button.innerHTML.substring(3) != correctans[currentIndex-1]){
        stopTimer();
        wrong_button = button;
        if (button_a.innerHTML.substring(3) == correctans[currentIndex - 1]) {
            button_a.style.backgroundColor = "lightgreen";
            button_b.style.backgroundColor = "lightcoral";
            button_c.style.backgroundColor = "lightcoral";
            button_d.style.backgroundColor = "lightcoral";
            button_a.disabled = true;
            button_b.disabled = true;
            button_c.disabled = true;
            button_d.disabled = true;

            right_button = button_a;
        } else if (button_b.innerHTML.substring(3) == correctans[currentIndex - 1]) {
            button_b.style.backgroundColor = "lightgreen";
            button_a.style.backgroundColor = "lightcoral";
            button_c.style.backgroundColor = "lightcoral";
            button_d.style.backgroundColor = "lightcoral";
            button_a.disabled = true;
            button_b.disabled = true;
            button_c.disabled = true;
            button_d.disabled = true;

            right_button = button_b;
        } else if (button_c.innerHTML.substring(3) == correctans[currentIndex - 1]) {
            button_c.style.backgroundColor = "lightgreen";
            button_a.style.backgroundColor = "lightcoral";
            button_b.style.backgroundColor = "lightcoral";
            button_d.style.backgroundColor = "lightcoral";
            button_a.disabled = true;
            button_b.disabled = true;
            button_c.disabled = true;
            button_d.disabled = true;

            right_button = button_c;
        } else if (button_d.innerHTML.substring(3) == correctans[currentIndex - 1]) {
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

        if(hints.style.display != 'none'){
      hints.disabled = true;
    }
    if(check_answer.style.display != 'none'){
      check_answer.disabled = true;
    }
    if(fifty_fifty.style.display != 'none'){
      fifty_fifty.disabled = true;
    }

        checkAnswers();
        quizOver();
        return;
    }

        if (currentIndex == 1) {
            document.getElementById("1").classList.remove("hide");
            document.getElementById("1").classList.add("show");
            var time = document.getElementById('timer').innerHTML;
            stopTimer();
            time = time.split(":");
            if(time.length > 1){
              var mins = parseInt(time[0]);
              var secs = parseInt(time[1]);
              var fulltime = (mins * 60) + secs + times[currentIndex-1];
            } else {
              secs = parseInt(time);
              fulltime = secs + times[currentIndex-1];
            }
            timer(fulltime);
        } else if (currentIndex == 2) {
            document.getElementById("2").classList.remove("hide");
            document.getElementById("2").classList.add("show");
            var time = document.getElementById('timer').innerHTML;
            stopTimer();
            time = time.split(":");
            if(time.length > 1){
              var mins = parseInt(time[0]);
              var secs = parseInt(time[1]);
              var fulltime = (mins * 60) + secs + times[currentIndex-1];
            } else {
              secs = parseInt(time);
              fulltime = secs + times[currentIndex-1];
            }
            timer(fulltime);
        } else if (currentIndex == 3) {
            document.getElementById("3").classList.remove("hide");
            document.getElementById("3").classList.add("show");
            var time = document.getElementById('timer').innerHTML;
            stopTimer();
            time = time.split(":");
            if(time.length > 1){
              var mins = parseInt(time[0]);
              var secs = parseInt(time[1]);
              var fulltime = (mins * 60) + secs + times[currentIndex-1];
            } else {
              secs = parseInt(time);
              fulltime = secs + times[currentIndex-1];
            }
            timer(fulltime);
        } else if (currentIndex == 4) {
            document.getElementById("4").classList.remove("hide");
            document.getElementById("4").classList.add("show");
            var time = document.getElementById('timer').innerHTML;
            stopTimer();
            time = time.split(":");
            if(time.length > 1){
              var mins = parseInt(time[0]);
              var secs = parseInt(time[1]);
              var fulltime = (mins * 60) + secs + times[currentIndex-1];
            } else {
              secs = parseInt(time);
              fulltime = secs + times[currentIndex-1];
            }
            timer(fulltime);
        } else if (currentIndex == 5) {
            document.getElementById("5").classList.remove("hide");
            document.getElementById("5").classList.add("show");
            var time = document.getElementById('timer').innerHTML;
            stopTimer();
            time = time.split(":");
            if(time.length > 1){
              var mins = parseInt(time[0]);
              var secs = parseInt(time[1]);
              var fulltime = (mins * 60) + secs + times[currentIndex-1];
            } else {
              secs = parseInt(time);
              fulltime = secs + times[currentIndex-1];
            }
            timer(fulltime);
        } else if (currentIndex == 6) {
            document.getElementById("6").classList.remove("hide");
            document.getElementById("6").classList.add("show");
            var time = document.getElementById('timer').innerHTML;
            time = time.split(":");
            var mins = parseInt(time[0]);
            var secs = parseInt(time[1]);
            var fulltime = (mins * 60) + secs + 15;
            stopTimer();
            timer(fulltime)

        } else if (currentIndex == 7) {
            document.getElementById("7").classList.remove("hide");
            document.getElementById("7").classList.add("show");
            var time = document.getElementById('timer').innerHTML;
            stopTimer();
            time = time.split(":");
            if(time.length > 1){
              var mins = parseInt(time[0]);
              var secs = parseInt(time[1]);
              var fulltime = (mins * 60) + secs + times[currentIndex-1];
            } else {
              secs = parseInt(time);
              fulltime = secs + times[currentIndex-1];
            }
            timer(fulltime);
        } else if (currentIndex == 8) {
            document.getElementById("8").classList.remove("hide");
            document.getElementById("8").classList.add("show");
            var time = document.getElementById('timer').innerHTML;
            stopTimer();
            time = time.split(":");
            if(time.length > 1){
              var mins = parseInt(time[0]);
              var secs = parseInt(time[1]);
              var fulltime = (mins * 60) + secs + times[currentIndex-1];
            } else {
              secs = parseInt(time);
              fulltime = secs + times[currentIndex-1];
            }
            timer(fulltime);
        } else if (currentIndex == 9) {
            document.getElementById("9").classList.remove("hide");
            document.getElementById("9").classList.add("show");
            var time = document.getElementById('timer').innerHTML;
            stopTimer();
            time = time.split(":");
            if(time.length > 1){
              var mins = parseInt(time[0]);
              var secs = parseInt(time[1]);
              var fulltime = (mins * 60) + secs + times[currentIndex-1];
            } else {
              secs = parseInt(time);
              fulltime = secs + times[currentIndex-1];
            }
            timer(fulltime);
        } else if (currentIndex == 10) {
            document.getElementById("10").classList.remove("hide");
            document.getElementById("10").classList.add("show");
            var time = document.getElementById('timer').innerHTML;
            stopTimer();
            time = time.split(":");
            if(time.length > 1){
              var mins = parseInt(time[0]);
              var secs = parseInt(time[1]);
              var fulltime = (mins * 60) + secs + times[currentIndex-1];
            } else {
              secs = parseInt(time);
              fulltime = secs + times[currentIndex-1];
            }
            timer(fulltime);
        } else if (currentIndex == 11) {
            document.getElementById("11").classList.remove("hide");
            document.getElementById("11").classList.add("show");
            var time = document.getElementById('timer').innerHTML;
            stopTimer();
            time = time.split(":");
            if(time.length > 1){
              var mins = parseInt(time[0]);
              var secs = parseInt(time[1]);
              var fulltime = (mins * 60) + secs + times[currentIndex-1];
            } else {
              secs = parseInt(time);
              fulltime = secs + times[currentIndex-1];
            }
            timer(fulltime);
        } else if (currentIndex == 12) {
            document.getElementById("12").classList.remove("hide");
            document.getElementById("12").classList.add("show");
            quizOver();
            stopTimer();
        }


    if (checked_answer != null) {
        checked_answer.style.display = 'inline';
    }
    document.getElementById("hint_id").style.display = "none";

    answeredQuestions.push(button.innerHTML.substring(3));

    load();
    return;
    }
var buttonCounter = 0;
//Listener function for click event on Next button
function next(clicked_button_id) {
    if(checked_pressed == true){
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

//Restart the quiz
window.onload = function() {
        this.load();
        timer(duration);
}

function checkAnswers() {
    for (var i = 0; i < answeredQuestions.length; i++) {
        if (answeredQuestions[i] == correctans[i]) {
            score++;
        }
    }
}

var counter = 0;

function quizOver() {
    if (counter > 1) {
        return;
    }
    stopTimer();
    var lifelines_used = [];
    if(lifelines_used.length == 0){
        lifelines_used.push('none');
    }

    lifelines_clicked.value = JSON.stringify(lifelines_used);
    questions_correct.value = score;
    tot_quest.value = questions.length;
    res.style.display = 'inline';
    if((score/questions.length)*100 >= pass_percentage ){
        document.getElementById("pointer").innerHTML = 'Good job you passed';
    } else {
        document.getElementById("pointer").innerHTML = 'Unfortunately you failed';
    }
    document.getElementById("result_score").innerHTML = `${score}` + "/" + questions.length;
    
    counter++;
}