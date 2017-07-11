import { Injectable } from '@angular/core';
import { Http } from '@angular/http';
import { HelperService } from './helper.service';

import 'rxjs/add/operator/toPromise';

@Injectable()
export class ReportService {

  constructor(private http:Http,
              private heleperService: HelperService) { }

  StatusOfReports(startdate:string = '2017-05-10') {
    this.heleperService.startLoading();
    return this.http.post('/api/report', {startdate})
      .toPromise()
      .then(response => {
        this.heleperService.endLoading('', 'none');
        return response.json();
      }).catch(err => {
        console.log('ERROR', err);
        this.heleperService.endLoading(err, 'error');
      });
  }

  SendReminders() {
    this.heleperService.startLoading();
    return this.http.post('/api/sendreminders', {})
      .toPromise()
      .then(response => {
        this.heleperService.endLoading('Send reminders success', 'success');
        return response.json();
      }).catch(err => {
        console.log('ERROR', err);
        this.heleperService.endLoading(err, 'error');
      });
  }

  getUpdateTime() {
    this.heleperService.startLoading();
    return this.http.get('/api/timereport', {})
      .toPromise()
      .then(response => {
        this.heleperService.endLoading('', 'none');
        return response.json();
      })
      .catch(err => {
        console.log('ERROR', err);
        this.heleperService.endLoading(err, 'error');
      });
  }

  setUpdateTime(week, hour) {
    this.heleperService.startLoading();
    return this.http.post('/api/timereport', {week, hour})
      .toPromise()
      .then(response => {
        this.heleperService.endLoading('New time was set', 'success');
        return response.json();
      }).catch(err => {
        console.log('ERROR', err);
        this.heleperService.endLoading(err, 'error');
      });
  }
}
