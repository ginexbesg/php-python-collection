from flask import Flask, request, jsonify
import mysql.connector

app = Flask(__name__)

# MySQL Database Connection
db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="test"
)
cursor = db.cursor()

# Create a table if it does not exist
cursor.execute("""
CREATE TABLE IF NOT EXISTS my_table (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data TEXT NOT NULL
)
""")
db.commit()

### GET: Retrieve all messages ###
@app.route('/my_table', methods=['GET'])
def get_messages():
    cursor.execute("SELECT * FROM my_table")
    messages = cursor.fetchall()
    result = [{"id": msg[0], "data": msg[1]} for msg in messages]
    return jsonify(result), 200

### POST: Insert a new message ###
@app.route('/my_table', methods=['POST'])
def add_message():
    data = request.json
    content = data.get("data")

    if not content:
        return jsonify({"error": "Data is required"}), 400

    cursor.execute("INSERT INTO my_table (data) VALUES (%s)", (content,))
    db.commit()
    
    return jsonify({"message": "Message added successfully", "id": cursor.lastrowid}), 201

### PATCH: Update a message ###
@app.route('/my_table/<int:msg_id>', methods=['PATCH'])
def update_message(msg_id):
    data = request.json
    content = data.get("data")

    if not content:
        return jsonify({"error": "Data is required"}), 400

    cursor.execute("UPDATE my_table SET data = %s WHERE id = %s", (content, msg_id))
    db.commit()

    if cursor.rowcount == 0:
        return jsonify({"error": "Message not found"}), 404

    return jsonify({"message": "Message updated successfully"}), 200

### DELETE: Delete a message ###
@app.route('/my_table/<int:msg_id>', methods=['DELETE'])
def delete_message(msg_id):
    cursor.execute("DELETE FROM my_table WHERE id = %s", (msg_id,))
    db.commit()

    if cursor.rowcount == 0:
        return jsonify({"error": "Message not found"}), 404

    return jsonify({"message": "Message deleted successfully"}), 200

if __name__ == '__main__':
    app.run(debug=True, port=5000)
