import { Component, OnInit } from '@angular/core';
import { HttpClient, HttpErrorResponse, HttpParams } from '@angular/common/http';
import { Class } from '../class';

@Component({
  selector: 'app-calculator',
  templateUrl: './calculator.component.html',
  styleUrls: ['./calculator.component.css']
})
export class CalculatorComponent implements OnInit {

  grades: Array<string> = ["A+", "A", "A-", "B+", "B", "B-", "C+", "C", "C-", "D+", "D", "D-", "F", ""];
  gradePoints: Array<number> = [4.0, 4.0, 3.7, 3.3, 3.0, 2.7, 2.3, 2.0, 1.7, 1.3, 1.0, 0.7, 0];

  classes: Array<Class> = [];

  gpa: string;

  classOptions: Array<string> = [];
  validClasses: boolean;

  constructor(private http: HttpClient) {
    this.gpa = "N/A";
    this.validClasses = false;
   }

  submitForm(data: any): void {
    this.classes = [];

    if(data.gradeOne !== ""){
      this.classes.push(new Class(data.classOne, data.gradeOne, data.creditsOne));
    }

    if(data.gradeTwo !== ""){
      this.classes.push(new Class(data.classTwo, data.gradeTwo, data.creditsTwo));
    }

    if(data.gradeThree !== ""){
      this.classes.push(new Class(data.classThree, data.gradeThree, data.creditsThree));
    }

    if(data.gradeFour !== ""){
      this.classes.push(new Class(data.classFour, data.gradeFour, data.creditsFour));
    }

    if(data.gradeFive !== ""){
      this.classes.push(new Class(data.classFive, data.gradeFive, data.creditsFive));
    }

    if(data.gradeSix !== ""){
      this.classes.push(new Class(data.classSix, data.gradeSix, data.creditsSix));
    }

    this.calculate();

  }

  calculate(){
    let totalCreditsTaken: number = 0;
    let totalCreditsEarned: number = 0;
    this.classes.forEach(c =>{
      let i = this.grades.indexOf(c.grade);
      let gradePoints = this.gradePoints[i];
      totalCreditsEarned += (gradePoints * c.credits);
      totalCreditsTaken += c.credits;
    })
    let gpaNumber = totalCreditsEarned / totalCreditsTaken;
    this.gpa = gpaNumber.toString().substring(0, 4);
  }

  response: any;
  getClassOptions(email: string): void {
    let json:string = JSON.stringify(email);
    console.log(json);
    this.http.post<any>("http://localhost/WebPlProject/gpa-calculator/backend.php", email).subscribe(
        (respData) =>  { 
            this.classOptions = [];
            this.validClasses = true;
            this.response = respData;
            this.addClassOptions();
            },
        (error) => { this.classOptions = [];
          this.validClasses = false;
          console.log("Error: ", error); }
    );
  }

  addClassOptions(){
    for(let i=0; i<this.response.length; i++){
      console.log(this.response[i]["name"]);
      this.classOptions.push(this.response[i]["name"]);
    }
  }

  ngOnInit(): void {
  }

}
