export class Message {
  constructor(message:string, status:string) {
    this.Message = message;
    this.messageStatus = status;
  }

  Message: string;
  messageStatus: string; // success, error
}
