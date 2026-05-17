import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
from sklearn import linear_model
from sklearn.metrics import r2_score

# Read the dataset
df = pd.read_csv("FuelConsumption.csv")

# Show first few rows
print(df.head())

# Summarize the data
print(df.describe())

# Select relevant features
cdf = df[['ENGINESIZE','CYLINDERS','FUELCONSUMPTION_COMB','CO2EMISSIONS']]
print(cdf.head(9))

# Plot histograms
viz = cdf[['CYLINDERS','ENGINESIZE','CO2EMISSIONS','FUELCONSUMPTION_COMB']]
viz.hist()
plt.show()

# Plot features vs Emission
plt.scatter(cdf.FUELCONSUMPTION_COMB, cdf.CO2EMISSIONS, color='blue')
plt.xlabel("FUELCONSUMPTION_COMB")
plt.ylabel("Emission")
plt.show()

plt.scatter(cdf.ENGINESIZE, cdf.CO2EMISSIONS, color='blue')
plt.xlabel("Engine size")
plt.ylabel("Emission")
plt.show()

# Practice: CYLINDERS vs Emission
plt.scatter(cdf.CYLINDERS, cdf.CO2EMISSIONS, color='blue')
plt.xlabel("Cylinders")
plt.ylabel("Emission")
plt.show()

# Train/Test Split
msk = np.random.rand(len(df)) < 0.8
train = cdf[msk]
test = cdf[~msk]

# Train data distribution
plt.scatter(train.ENGINESIZE, train.CO2EMISSIONS, color='blue')
plt.xlabel("Engine size")
plt.ylabel("Emission")
plt.show()

# Train model using ENGINESIZE
regr = linear_model.LinearRegression()
train_x = np.asanyarray(train[['ENGINESIZE']])
train_y = np.asanyarray(train[['CO2EMISSIONS']])
regr.fit(train_x, train_y)

# Coefficients
print("Coefficients:", regr.coef_)
print("Intercept:", regr.intercept_)

# Plot regression line
plt.scatter(train.ENGINESIZE, train.CO2EMISSIONS, color='blue')
plt.plot(train_x, regr.coef_[0][0]*train_x + regr.intercept_[0], '-r')
plt.xlabel("Engine size")
plt.ylabel("Emission")
plt.show()

# Evaluation
test_x = np.asanyarray(test[['ENGINESIZE']])
test_y = np.asanyarray(test[['CO2EMISSIONS']])
test_y_ = regr.predict(test_x)

print("Mean absolute error: %.2f" % np.mean(np.absolute(test_y_ - test_y)))
print("Residual sum of squares (MSE): %.2f" % np.mean((test_y_ - test_y) ** 2))
print("R2-score: %.2f" % r2_score(test_y , test_y_))

# -----------------------------
# Exercise: Use FUELCONSUMPTION_COMB for regression
# -----------------------------

# Step 1: Select feature for training and testing
train_x = np.asanyarray(train[['FUELCONSUMPTION_COMB']])
test_x = np.asanyarray(test[['FUELCONSUMPTION_COMB']])

# Step 2: Train the Linear Regression model
regr2 = linear_model.LinearRegression()
regr2.fit(train_x, train_y)

# Step 3: Print the coefficients
print("Coefficients (FUELCONSUMPTION_COMB):", regr2.coef_)
print("Intercept:", regr2.intercept_)

# Step 4: Predict the values on the test data
test_y_pred = regr2.predict(test_x)

# Step 5: Evaluate the model
mae = np.mean(np.absolute(test_y_pred - test_y))
mse = np.mean((test_y_pred - test_y) ** 2)
r2 = r2_score(test_y, test_y_pred)

print("Mean Absolute Error: %.2f" % mae)
print("Mean Squared Error: %.2f" % mse)
print("R2-score: %.2f" % r2)

# Step 6: Plot the regression line
plt.scatter(train_x, train_y, color='blue')
plt.plot(train_x, regr2.predict(train_x), '-r')
plt.xlabel("FUELCONSUMPTION_COMB")
plt.ylabel("Emission")
plt.show()
