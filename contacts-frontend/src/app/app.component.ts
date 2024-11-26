import { Component, OnInit } from '@angular/core';
import { ContactService } from './services/contact.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit {
  contacts: any[] = [];
  newContact = { name: '', phone: '' };

  constructor(private contactService: ContactService) {}

  ngOnInit(): void {
    this.loadContacts();
  }

  loadContacts(): void {
    this.contactService.getContacts().subscribe((data) => {
      this.contacts = data;
    });
  }

  addContact(): void {
    this.contactService.createContact(this.newContact).subscribe(() => {
      this.loadContacts();
      this.newContact = { name: '', phone: '' };
    });
  }

  deleteContact(id: number): void {
    this.contactService.deleteContact(id).subscribe(() => {
      this.loadContacts();
    });
  }
}
