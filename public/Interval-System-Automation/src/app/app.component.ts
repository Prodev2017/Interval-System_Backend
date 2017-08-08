import { Component, ElementRef, OnInit, AfterViewInit } from '@angular/core';
import { HelperService } from './services/helper.service';
import { Message } from './Models/Message';

import 'jquery';
import 'bootstrap/dist/js/bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import {Observable} from "rxjs";
declare var jQuery: any;

@Component({
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.css']
})
export class AppComponent  implements  OnInit, AfterViewInit{
	elementRef: ElementRef;
  private Messages: Message[] = [];

	constructor(private helperService:HelperService,
              elementRef: ElementRef) {
		this.elementRef = elementRef;

		helperService.subscribeMessage()
      .subscribe((message) => {
          this.Messages.push(message);
          setTimeout(() => {
            let ind = this.Messages.indexOf(message);
            this.Messages.splice(ind, 1);
          }, 4000);
      });
  }

	ngOnInit() {};

	ngAfterViewInit() {};
}
