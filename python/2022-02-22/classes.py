from pprint import pprint
class Person:
    # def __init__(self, name, age, sex):
        # self.a = name 
        # self.b = age
    
    def introduce(self, a, b, c):
        print(f"Hello, I am {self.b} years old, my name is {self.c}")

p1 = Person("John", 42)
p1.introduce()