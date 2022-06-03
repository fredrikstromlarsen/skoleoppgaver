
import numpy as np
import pandas as pd
import matplotlib.pyplot as plt


def with_pandas():
    data = pd.read_csv("reiser.csv", index_col=0, skiprows = (0,1), sep=";", encoding="latin-1")
    data = data.transpose()
    data.set_index('Reisetype')
    plt.plot(data)
    plt.show().legend(bbox_to_anchor = (1,1))


if __name__ == '__main__':
    #with_pandas()
    with_numpy()