import { Component, OnInit } from '@angular/core';
import { UserService } from '../../services/user.service';
import { ReportService } from '../../services/report.service';

@Component({
  selector: 'app-setup',
  templateUrl: 'setup.component.html',
  styleUrls: ['setup.component.css']
})
export class SetupComponent implements OnInit {

  private availableUsers: any[];
  private managers: any[];
  private selectedManager: any;
  private selectedUsers: any[];
  private unSelectedUsers: any[];

  private DayOfWeek: number;
  private previousDayTime: number = 0;
  private DayTime: number;

  constructor(private userService: UserService,
              private reportService: ReportService) {

    this.userService.getSelectedUser()
      .then(result => {
        this.managers = result.managers.map(manager => {
          if(manager.users && !Array.isArray(manager.users)) {
            manager.users = Object.keys(manager.users).map(key => manager.users[key]);
          }
          return manager;
        });
        this.availableUsers = result.available_users;
        this.selectedManager = this.managers[0];

        this.selectedUsers = [];
        this.unSelectedUsers = [];
      });

    this.reportService.getUpdateTime()
      .then(result => {
        this.DayOfWeek = result.week;
        this.DayTime = result.hour;
        this.previousDayTime = result.hour;
      });
  }

  ngOnInit() {}

  selectAvailableUsers() {
    this.selectedManager.users = this.selectedManager.users.concat(this.selectedUsers).sort(this.sortUserArray);
    this.selectedUsers.forEach((user) => {
      let ind = this.availableUsers.indexOf(user);
      if(~ind) {
        this.availableUsers.splice(ind, 1);
      }
    });

    this.selectedUsers = [];
    this.unSelectedUsers = [];
    this.Submit();
  }

  unSelectUsers() {
    this.availableUsers = this.availableUsers.concat(this.unSelectedUsers).sort(this.sortUserArray);
    this.unSelectedUsers.forEach((user) => {
      let ind = this.selectedManager.users.indexOf(user);
      if(~ind) {
        this.selectedManager.users.splice(ind, 1);
      }
    });

    this.selectedUsers = [];
    this.unSelectedUsers = [];
    this.Submit();
  }

  sortUserArray(first, second) {
    let f = (first.firstname + first.lastname).toLowerCase();
    let s = (second.firstname + second.lastname).toLowerCase();

    if (f < s)
      return -1;
    if (f > s)
      return 1;
    return 0;
  }

  Submit() {
    this.userService.setSelectedUser(
      this.availableUsers.map(user => user.interval_id),
      this.managers.map(manager => {
        //console.log(manager);
        return {
          manager_id: manager.interval_id,
          users: manager.users.map(user => user.interval_id || user.user_id)
        };
      })
    ).then(result => {
        // console.log(result);
    });
  }

  UpdateUsers() {
    this.userService.UpdateUsers();
  }

  UpdateTime() {
    this.reportService.setUpdateTime(this.DayOfWeek, this.DayTime);
  }

  dayTimeChange() {
    if(this.DayTime < 24 && this.DayTime >= 0) {
      this.previousDayTime = this.DayTime;
    } else {
      this.DayTime = this.previousDayTime;
    }
  }
}
