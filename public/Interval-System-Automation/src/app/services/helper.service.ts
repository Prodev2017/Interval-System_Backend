import { Injectable } from '@angular/core';
import { Subject } from 'rxjs/Subject';
import { Message } from '../Models/Message';
import { Observable } from "rxjs";

@Injectable()
export class HelperService {
  public load: boolean = false;
  private messageSource:Subject<Message> = new Subject<Message>();

  constructor() {}

  subscribeMessage():Observable<Message> {
    return this.messageSource.asObservable();
  }

  startLoading() {
    this.load = true;
  }

  endLoading(message = {}, status = 'false') {
    this.load = false;
    switch(status) {
      case 'success':
        this.messageSource.next(new Message(message.toString(), 'success'));
        break;
      case 'error':
        this.messageSource.next(new Message(message.toString(), 'error'));
        break;
    }
  }


}
