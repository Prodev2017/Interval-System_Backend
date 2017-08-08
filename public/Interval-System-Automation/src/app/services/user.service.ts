import { Injectable } from '@angular/core';
import { Http } from '@angular/http';
import { HelperService } from './helper.service';

import 'rxjs/add/operator/toPromise';

@Injectable()
export class UserService {

  constructor(private http:Http,
              private heleperService: HelperService) { }

  getSelectedUser() {
    this.heleperService.startLoading();
    return this.http.post('/api/getselected', {})
      .toPromise()
      .then(response => {
        this.heleperService.endLoading('', 'none');
        return response.json();
      }).catch(err => {
        console.log('ERROR', err);
        this.heleperService.endLoading(err, 'error');
      });
  }

  setSelectedUser(available_users, managers) {
    this.heleperService.startLoading();
    return this.http.post('/api/setselected', {available_users, managers}).toPromise()
    .then(response => {
      this.heleperService.endLoading('Users was changed', 'success');
      return response.json();
    }).catch(err => {
        console.log('ERROR', err);
        this.heleperService.endLoading(err, 'error');
    })
  }

  UpdateUsers() {
    this.heleperService.startLoading();
    return this.http.post('/api/updateuser', {})
      .toPromise()
      .then(response => {
        this.heleperService.endLoading('Users was updated', 'success');
        return response.json();
      }).catch(err => {
        console.log('ERROR', err);
        this.heleperService.endLoading(err, 'error');
      });
  }
}
