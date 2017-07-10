import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';
import { RouterModule, Routes } from '@angular/router';

import { MyDatePickerModule } from 'mydatepicker';

import { AppComponent } from './app.component';
import { NavigationComponent } from './components/navigation/navigation.component';
import { HomeComponent } from './components/home/home.component';
import { ReportsComponent } from './components/reports/reports.component';
import { SetupComponent } from './components/setup/setup.component';

import { HelperService } from './services/helper.service';
import { UserService } from './services/user.service';
import { ReportService } from './services/report.service';

const appRoutes: Routes = [
  {path: '', component: HomeComponent},
  {path: 'reports', component: ReportsComponent},
  {path: 'setup', component: SetupComponent},
];

@NgModule({
  declarations: [
    AppComponent,
    NavigationComponent,
    HomeComponent,
    ReportsComponent,
    SetupComponent
  ],
  imports: [
    BrowserModule,
    FormsModule,
    HttpModule,
    MyDatePickerModule,
    RouterModule.forRoot(appRoutes)
  ],
  providers: [
    HelperService,
    UserService,
    ReportService
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
