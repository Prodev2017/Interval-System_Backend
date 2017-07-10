import { Component, OnInit, ViewEncapsulation  } from '@angular/core';
import { ReportService } from '../../services/report.service';

import { IMyDpOptions, IMyCalendarViewChanged } from 'mydatepicker';

declare let $: any;
declare let Pleasure: any;
declare let Layout: any;
declare let FormsSwitch: any;
declare let FormsSwitchery: any;

@Component({
  selector: 'app-reports',
  templateUrl: 'reports.component.html'
})
export class ReportsComponent implements OnInit {

  private pickedDate: any;
  private projects: any;

  private disabledYear = [];

  private myDatePickerOptions : IMyDpOptions = {
    showTodayBtn: false,
    yearSelector: false,
    monthSelector: false,
    disableDays: []
  };

  DisableYear(year) {
    if(~this.disabledYear.indexOf(year)) {
      return [];
    } else {
      this.disabledYear.push(year);
      let result = [];
      let DAY = new Date(year, 0, 1);
      while (DAY.getFullYear() == year) {
        if (DAY.getDay() != 0) {
          result.push({
            year: DAY.getFullYear(),
            month: DAY.getMonth() + 1,
            day: DAY.getDate()
          });
        }
        DAY.setDate(DAY.getDate() + 1);
      }
      return result;
    }
  }

  constructor(private reportService: ReportService) {
    let d: Date = new Date();
    while(d.getDay() != 0) {
      d.setDate(d.getDate() - 1);
    }

    this.pickedDate = {
      year: d.getFullYear(),
      month: d.getMonth() + 1,
      day: d.getDate()
    };

    this.myDatePickerOptions.disableDays = this.DisableYear(d.getFullYear());
    this.myDatePickerOptions.disableDays.push(...this.DisableYear(d.getFullYear() - 1));
    this.myDatePickerOptions.disableDays.push(...this.DisableYear(d.getFullYear() + 1));

    this.UpdateProject(this.pickedDate);
  }

  UpdateProject(date) {
    this.reportService.StatusOfReports(this.DateFormatter(date))
      .then(response => {
        this.projects = response.approvals.filter(approval => {
          return !!(approval.approval_id || approval.approval_id == 0);
        }).reduce((acc, project) => {
          let ind = project.week_date_start + '~' + project.week_date_end;
          if (!acc.intervals[ind]) {
            acc.intervals[ind] = {
              start: project.week_date_start,
              end: project.week_date_end,
              users: {}
            }
          }
          acc.intervals[ind].users[project.user_id] = project.status;
          if(!acc.users[project.user_id]) {
            acc.users[project.user_id] = {
              username: project.username,
              id: project.user_id
            };
            acc.userCount++
          }

          return acc;
        }, {userCount: 0, users: {}, intervals: {}});
      });
  }

  onDateChanged($event) {
    if($event.formatted) {
      this.UpdateProject($event.date);
    }
  }

  onCalendarViewChanged(event: IMyCalendarViewChanged) {
    this.myDatePickerOptions.disableDays.push(...this.DisableYear(event.year - 1));
    this.myDatePickerOptions.disableDays.push(...this.DisableYear(event.year + 1));
  }

  ngOnInit() {
    $(document).ready(function () {
      Pleasure.init();
      Layout.init();
      FormsSwitch.init();
      FormsSwitchery.init();
    });
  }

  private DateFormatter(DateObject) {
    let month = String((DateObject.month < 10)? '0' + DateObject.month : DateObject.month);
    let day = String((DateObject.day < 10)? '0' + DateObject.day : DateObject.day);
    return `${DateObject.year}-${month}-${day}`;
  }

  private StatusFormatter(interval, user) {
    return interval.users[user.id] ? 'Approved' :
      (interval.users[user.id] === 0) ? 'Not approved' : '-';
  }

  private toIterable(obj) {
    return Object.keys(obj).map(key => obj[key]);
  }

  public SendReminders() {
    this.reportService.SendReminders()
      .then((response) => {
        // console.log(response);
      });
  }

}
