from flask import Flask, request, render_template, make_response, redirect, url_for, g
import sqlite3

# runs on http://127.0.0.1:5000/
# php files with functions called: read_del, read_submissions, review_reader
# omnom.php has no html currently, post form submission page
# whois.php login related, redirects to index, no html
app = Flask(__name__)

DATABASE = 'database.db'

def get_db():
    db = getattr(g, '_database', None)
    if db is None:
        db = g._database = sqlite3.connect(DATABASE)
    db.row_factory = sqlite3.Row
    return db

def query_db(query, args=(), one=False):
    cur = get_db().execute(query, args)
    rv = cur.fetchall()
    cur.close()
    return (rv[0] if rv else None) if one else rv

def writeto_db(query, args=()):
    get_db().execute(query, args)
    get_db().commit()

@app.teardown_appcontext
def close_connection(exception):
    db = getattr(g, '_database', None)
    if db is not None:
        db.close()

@app.route('/')
def index(): #index.php
    rname = request.cookies.get('reviewer_name')
    remail = request.cookies.get('reviewer_email')
    if rname is None or remail is None:
        return render_template('index.html', name="", email="")
    else:
        return render_template('index.html', name=rname, email=remail)

@app.route('/tagging')
def tagging(): #tagging.php
    return render_template('tagging.html')

@app.route('/welcome')
def welcome(): #welcome.php
    rname = request.cookies.get('reviewer_name')
    remail = request.cookies.get('reviewer_email')
    if remail is None:
        return redirect(url_for('index'))
    numResults = len(query_db("select * from Scores where reviewId = ?", [str.lower(remail)]))
    return render_template('welcome.html', name=rname, email=remail, numRead=numResults)

@app.route('/edit')
def edit(): #edit.php
    return render_template('edit.html')

@app.route('/all_abstracts')
def all_abstracts(): #all_abstracts.php
    return render_template('all_abstracts.html')

@app.route('/upload')
def upload(): #upload.html ... there's a ton of JS I didn't copy
    return render_template('upload.html')

@app.route('/welcome', methods=['POST'])
def submit_name_email():
# stores user input in email and in name for checking in database

    email = request.form['reviewer_email']
    name = request.form['reviewer_name']
    if email is "" or name is "":
        return redirect(url_for('index'))
    else:
        user = query_db('select * from Users where id = ?', [str.lower(email)], one=True)
        if user is None:
            writeto_db('''INSERT INTO Users(id)
            values(?)''', [str.lower(email)])
        numResults = len(query_db("select * from Scores where reviewId = ?", [str.lower(email)]))
        resp = make_response(render_template('welcome.html', name = name, email = email, numRead = numResults))
        resp.set_cookie('reviewer_name', name)
        resp.set_cookie('reviewer_email', email)
        return resp

if __name__ == '__main__':
    app.run(debug=True)
