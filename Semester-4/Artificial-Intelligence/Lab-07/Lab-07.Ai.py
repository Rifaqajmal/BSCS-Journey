import numpy as np
import pandas as pd
import matplotlib.pyplot as plt

# Load the dataset
try:
    df = pd.read_csv('all_stocks_5yr.csv')
except FileNotFoundError:
    print("Error: 'all_stocks_5yr.csv' file not found.")
    exit()

# Display the first few rows of the dataset
print(df.head())

# Calculate daily percentage change
df['Daily_Pct_Change'] = df.groupby('Name')['close'].pct_change()

# Display the first few rows with the new column
print(df.head())

# Calculate the standard deviation of daily percentage changes for each stock
volatility = df.groupby('Name')['Daily_Pct_Change'].std()

# Identify the most volatile stock
most_volatile_stock = volatility.idxmax()
most_volatile_std = volatility.max()

print(f"The most volatile stock is {most_volatile_stock} with a standard deviation of {most_volatile_std:.4f}")

# Plot the volatility of each stock
volatility.plot(kind='bar', figsize=(10, 6))
plt.title('Volatility of Stocks')
plt.xlabel('Stock')
plt.ylabel('Standard Deviation of Daily Percentage Change')
plt.show()

"""# TASK NO 2"""

# Load the dataset
try:
    df = pd.read_csv('covid_19_clean_complete.csv')
except FileNotFoundError:
    print("Error: 'covid_19_clean_complete.csv' file not found.")
    exit()

# Display the first few rows of the dataset
print(df.head())

# Group by date and sum up confirmed cases
daily_cases = df.groupby("Date")["Confirmed"].sum().diff().fillna(0)

# Find the day with the highest spike
highest_spike_day = daily_cases.idxmax()
highest_spike_value = daily_cases.max()

print(f"Day with the highest spike: {highest_spike_day}, Cases: {highest_spike_value}")

# Compute cumulative sum of daily new cases
cumulative_cases = np.cumsum(daily_cases)

print("Cumulative cases over time:")
print(cumulative_cases)

plt.figure(figsize=(10,5))
plt.plot(daily_cases, label="Daily New Cases", color="red")
plt.axvline(highest_spike_day, color="blue", linestyle="--", label="Highest Spike Day")
plt.xlabel("Date")
plt.ylabel("New Cases")
plt.legend()
plt.title("COVID-19 Daily Infection Spike")
plt.xticks(rotation=45)
plt.show()

"""# task no 3"""

# Load the dataset
try:
    df = pd.read_csv('Climate Change Dataset.csv.csv')
except FileNotFoundError:
    print("Error: 'Climate Change Dataset.csv.csv' file not found.")
    exit()

# Display the first few rows of the dataset
print(df.head())
df['DATE'] = pd.to_datetime(df['DATE'])
df['Year'] = df['DATE'].dt.year

# Group by year and compute the average temperature
yearly_avg_temp = df.groupby("Year")["TempAVG"].mean()

# Find the year with the highest average temperature
hottest_year = yearly_avg_temp.idxmax()
hottest_temp = yearly_avg_temp.max()

print(f"Year with highest average temperature: {hottest_year}, Avg Temp: {hottest_temp:.2f}°C")

# Compute daily temperature differences
df['Temp_Diff'] = df['TempMAX'] - df['TempMIN']

# Set threshold for anomalies (e.g., 99th percentile)
threshold = df['Temp_Diff'].quantile(0.99)

# Find anomalous temperature spikes
anomalies = df[df['Temp_Diff'] > threshold]

print("Anomalous Temperature Spikes:")
print(anomalies[['DATE', 'TempMAX', 'TempMIN', 'Temp_Diff']])

# Define historical and recent periods
historical_avg = df[df['Year'] < 2000].groupby("Year")["TempAVG"].mean()
recent_avg = df[df['Year'] >= 2000].groupby("Year")["TempAVG"].mean()

# Plot comparison
plt.figure(figsize=(10,5))
plt.plot(historical_avg, label="Historical (Before 2000)", color="blue")
plt.plot(recent_avg, label="Recent (2000+)", color="red")
plt.xlabel("Year")
plt.ylabel("Avg Temperature (°C)")
plt.title("Historical vs Recent Temperature Trends")
plt.legend()
plt.show()

"""# task no 4"""

# Load the dataset
try:
    df = pd.read_csv('ebay_mens_perfume.csv')
except FileNotFoundError:
    print("Error: 'ebay_mens_perfume.csv' file not found.")
    exit()

# Display the first few rows of the dataset
print(df.head())
# Drop unnecessary columns
df = df[['brand', 'title', 'type', 'price', 'sold']]

# Compute revenue per category (type)
df['Revenue'] = df['price'] * df['sold']

# Group by category and sum revenue
category_revenue = df.groupby("type")["Revenue"].sum().reset_index()

print("Total revenue per category:")
print(category_revenue)

# Find the category with the highest revenue
best_selling_category = category_revenue.loc[category_revenue["Revenue"].idxmax()]

print(f"Best-selling category: {best_selling_category['type']} with revenue {best_selling_category['Revenue']:.2f}")

# Compute total revenue
total_revenue = category_revenue["Revenue"].sum()

# Compute percentage contribution
category_revenue["Percentage"] = (category_revenue["Revenue"] / total_revenue) * 100

print("Percentage contribution per category:")
print(category_revenue)

# Pie chart for revenue distribution per category
plt.figure(figsize=(8,8))
plt.pie(category_revenue["Revenue"], labels=category_revenue["type"], autopct="%1.1f%%", startangle=140)
plt.title("Revenue Contribution per Category")
plt.show()

"""# task no 5"""

import re
import nltk
from nltk.tokenize import word_tokenize
from nltk.corpus import stopwords
from nltk.stem import PorterStemmer, WordNetLemmatizer

# Download necessary NLTK resources
nltk.download('punkt')
nltk.download('stopwords')
nltk.download('wordnet')

# Load the dataset
try:
    df = pd.read_csv('text_data.csv')
except FileNotFoundError:
    print("Error: 'text_data.csv' file not found.")
    exit()

# Display the first few rows of the dataset
print(df.head())

# Drop missing values
df = df.dropna(subset=['Review_Text'])

# Convert text to lowercase
df['Clean_Text'] = df['Review_Text'].str.lower()

# Remove punctuation and special characters
df['Clean_Text'] = df['Clean_Text'].apply(lambda x: re.sub(r'[^a-z\s]', '', x))

# Tokenize the text (split into words)
df['Tokens'] = df['Clean_Text'].apply(word_tokenize)

# Remove stopwords
stop_words = set(stopwords.words('english'))
df['Tokens'] = df['Tokens'].apply(lambda words: [word for word in words if word not in stop_words])

print(df.head())

"""# task no 6"""

# Load the dataset
try:
    df = pd.read_csv('Game_of_Thrones_Script.csv', parse_dates=['Release Date'])
except FileNotFoundError:
    print("Error: 'Game_of_Thrones_Script.csv' file not found.")
    exit()

print(df.head())

# Sort dataset by 'Release Date'
df = df.sort_values(by='Release Date')

# Resample data: Aggregate by count (adjust as needed)
daily_summary = df.set_index('Release Date').resample('D').count()

print(daily_summary.head())