#translate example:

# Translation table: 'a' -> '1', 'b' -> '2'
translation_table = str.maketrans({'a': '1', 'b': '2'})
result = 'banana'.translate(translation_table)
print(result)
