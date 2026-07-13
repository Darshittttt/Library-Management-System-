import sys
import pandas as pd
import mysql.connector
from sklearn.metrics.pairwise import cosine_similarity

# 🔌 Connect to MySQL
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="library"
)

# 📥 Fetch data (JOIN with category)
query = """
SELECT b.id, c.CategoryName 
FROM tblbooks b
JOIN tblcategory c ON b.CatId = c.id
"""

df = pd.read_sql(query, conn)
conn.close()

# 🚨 If no data
if df.empty:
    print("1,2")
    exit()

# Rename columns
df.columns = ['book_id', 'category']

# 🔄 Convert category → numeric (one-hot encoding)
encoded = pd.get_dummies(df['category'])

# 🤖 Calculate similarity
similarity = cosine_similarity(encoded)

# 📌 Get input book id
if len(sys.argv) > 1:
    try:
        book_id = int(sys.argv[1])
    except:
        book_id = 1
else:
    book_id = 1

# 🚨 If book not found
if book_id not in df['book_id'].values:
    print(",".join(df['book_id'].astype(str).head(2)))
    exit()

# Find index
idx = df[df['book_id'] == book_id].index[0]

# 🎯 Get similarity scores
scores = list(enumerate(similarity[idx]))
scores = sorted(scores, key=lambda x: x[1], reverse=True)

# ✅ Get valid book IDs
valid_ids = df['book_id'].astype(str).tolist()

# 🎁 Generate recommendations
recommendations = []
for i in scores[1:]:
    book = str(df.iloc[i[0]]['book_id'])
    if book in valid_ids:
        recommendations.append(book)
    if len(recommendations) == 2:
        break

# 🔁 Fallback if empty
if not recommendations:
    recommendations = valid_ids[:2]

# 🖨 Output
print(",".join(recommendations))