import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ContactService {
  private apiUrl = 'http://localhost/contacts_backend/api/contacts.php';

  constructor(private http: HttpClient) {}

  getContacts(): Observable<any> {
    return this.http.get(this.apiUrl);
  }

  createContact(contact: any): Observable<any> {
    return this.http.post(this.apiUrl, contact);
  }

  updateContact(contact: any): Observable<any> {
    return this.http.put(this.apiUrl, contact);
  }

  deleteContact(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}?id=${id}`);
  }
}
