python -m venv venv
source venv/Scripts/activate
pip install flask flask-cors
python app.py

from postman call APIs

To retrieve all
http://127.0.0.1:5000/my_table
GET method

To insert data
http://127.0.0.1:5000/my_table
POST method
Headers
Content-Type : "application/json"
Body
{
"data": "Your message here"
}

To update data
http://127.0.0.1:5000/my_table/20
PATCH method
Headers
Content-Type : "application/json"
Body
{
"data": "Your message updated"
}

To delete data
http://127.0.0.1:5000/my_table/20
DELETE method
