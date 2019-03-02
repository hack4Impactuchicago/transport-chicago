from flask import Flask
from flask import render_template
# runs on http://127.0.0.1:5000/
# php files with functions called: read_del, read_submissions, review_reader
# omnom.php has no html currently, post form submission page
# whois.php login related, redirects to index, no html
app = Flask(__name__)

@app.route('/')
def index(): #index.php
    return render_template('index.html', name="Name", email="Email")

@app.route('/tagging')
def tagging(): #tagging.php
    return render_template('tagging.html')

@app.route('/welcome')
def welcome(): #welcome.php
    return render_template('welcome.html')

@app.route('/edit')
def edit(): #edit.php
    return render_template('edit.html')

@app.route('/all_abstracts')
def all_abstracts(): #all_abstracts.php
    return render_template('all_abstracts.html')

@app.route('/upload')
def upload(): #upload.html ... there's a ton of JS I didn't copy
    return render_template('upload.html')

if __name__ == '__main__':
    app.run(debug=True)
